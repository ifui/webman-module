<?php

/*
|--------------------------------------------------------------------------
| Container Routes
|--------------------------------------------------------------------------
*/

use Ifui\WebmanModule\Module;
use Webman\Route;

$activityApplications = Module::getInstance()->getActivity();

// 注册已启用的模块应用路由配置
foreach ($activityApplications as $activityApplication) {
    if (empty($activityApplication['name'])) continue;
    $paths = glob(module_path($activityApplication['name']) . '/route/*.php');
    if (isset($activityApplication['route']['urlPrefix']) && !empty($activityApplication['route']['urlPrefix'])) {
        foreach ($paths as $path) {
            Route::group($activityApplication['route']['urlPrefix'], function () use ($path) {
                require_once $path;
            });
        }
    } else {
        foreach ($paths as $path) {
            require_once $path;
        }
    }
}

// 注册已启用的模块应用自动路由配置
// 参考 walkor 的 auto-route @see https://github.com/webman-php/auto-route/blob/main/src/config/plugin/webman/auto-route/route.php
foreach ($activityApplications as $activityApplication) {
    if (!$activityApplication['route']['autoRoute']) continue;
    if (empty($activityApplication['name'])) continue;

    // 路由前缀
    $url_prefix = $activityApplication['route']['urlPrefix'];

    // 已经设置过路由的uri则忽略
    $routes = Route::getRoutes();
    $ignore_list = [];
    foreach ($routes as $tmp_route) {
        $ignore_list[$tmp_route->getPath()] = 0;
    }

//    $default_app = config('plugin.webman.auto-route.app.default_app');

    $suffix = config('app.controller_suffix', '');
    $suffix_length = strlen($suffix);

    // 递归遍历目录查找控制器自动设置路由
    $dir_iterator = new RecursiveDirectoryIterator(module_path($activityApplication['name']));
    $iterator = new RecursiveIteratorIterator($dir_iterator);

    foreach ($iterator as $file) {
        // 忽略目录和非php文件
        if (is_dir($file) || $file->getExtension() != 'php') {
            continue;
        }

        $file_path = str_replace('\\', '/', $file->getPathname());
        // 文件路径里不带controller的文件忽略
        if (strpos(strtolower($file_path), '/controller/') === false) {
            continue;
        }

        // 文件路径里不带应用名的文件忽略
        if (strpos(strtolower($file_path), '/' . strtolower($activityApplication['name']) . '/') === false) {
            continue;
        }

        // 只处理带 controller_suffix 后缀的
        if ($suffix_length && substr($file->getBaseName('.php'), -$suffix_length) !== $suffix) {
            continue;
        }

        // 根据文件路径计算uri
        $uri_path = str_replace(['/controller/', '/Controller/'], '/', substr(substr($file_path, strlen(module_path())), 0, -(4 + $suffix_length)));

        // 默认应用
//        $is_default_app = false;
//        if (is_string($default_app) && !empty($default_app)) {
//            $seg = explode('/', $uri_path);
//            if ($seg[1] == $default_app) {
//                $uri_path = str_replace($default_app . '/', '', $uri_path);
//                $is_default_app = true;
//            }
//        }

        // 根据文件路径是被类名
        $class_name = str_replace('/', '\\', substr(substr($file_path, strlen(base_path())), 0, -4));

        if (!class_exists($class_name)) {
            echo "Class $class_name not found, skip route for it\n";
            continue;
        }

        // 通过反射找到这个类的所有共有方法作为action
        $class = new ReflectionClass($class_name);
        $class_name = $class->name;
        $methods = $class->getMethods(ReflectionMethod::IS_PUBLIC);

        $route = function ($uri, $cb) use ($ignore_list, $url_prefix) {
            if (isset($ignore_list[$uri])) {
                return;
            }

            $uri = explode('/', $uri);
            // 添加路由前缀
            if ($url_prefix) {
                unset($uri[0]);
                $uri[1] = $url_prefix;
            }
            // 忽略 App 目录
            unset($uri[2]);
            $uri = implode('/', $uri);

            Route::any($uri, $cb);
            if ($uri !== '') {
                Route::any($uri . '/', $cb);
            }
            $lower_uri = strtolower($uri);
            if ($lower_uri !== $uri) {
                Route::any($lower_uri, $cb);
                Route::any($lower_uri . '/', $cb);
            }
        };

        // 设置路由
        foreach ($methods as $item) {
            $action = $item->name;
            if (in_array($action, ['__construct', '__destruct'])) {
                continue;
            }
            // action为index时uri里末尾/index可以省略
            if ($action === 'index') {
                // controller也为index时uri里可以省略/index/index
                if (strtolower(substr($uri_path, -6)) === '/index') {
//                    if ($is_default_app) {
//                        $route('/', [$class_name, $action]);
//                    }
                    $route(substr($uri_path, 0, -6), [$class_name, $action]);
                }
                $route($uri_path, [$class_name, $action]);
            }
            $route($uri_path . '/' . $action, [$class_name, $action]);
        }
    }
}

