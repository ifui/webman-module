# Webman Module 应用模块插件（社区版）

> 基于 `Webman` 的一款应用模块插件，与普通插件的主要区别在于它是一款面向生产环境下的应用级解决方案，例如问答、博客、新闻等应用程序

## 介绍

`ifui/webman-module` 是一个 `webman` 插件，它可以使用模块化、搭积木的方式来管理你的应用程序。

一个模块就像是一个应用，该插件严格参照官方的 [应用插件说明](https://www.workerman.net/doc/webman/plugin/app.html) 规范开发。

相比于普通插件的一大好处是内置支持了 `测试` 功能，可以方便快捷的测试应用程序在实际应用场景下的情况。

## 快速上手

安装命令：`composer require webman/console && composer require ifui/webman-module --dev`

### 1.创建插件

执行：`php webman module:create`

插件名应遵循`PSR4`规范，这里不作过多限制

```
 请输入应用名（必填）:
 > apple

 请输入作者名（必填）:
 > ifui

 请输入邮箱地址（选填）:
 > xx@xx.com

 请输入应用简介（选填）:
 > apple

 请输入主页地址（选填）:
 > apple
```
按照操作提示输入相应的内容即可，将会创建如下目录格式
```
/plugin/
  ├── apple/
      ├── app/
          ├── controller/
              ├── Index.php 
          ├── middleware/
          ├── model/
          ├── providers/
              ├── AppServerProvider.php
          ├── view/
          ├── functions.php
      ├── config/
          ├── app.php
      ├── database/
          ├── migrations/
          ├── seeds/
      ├── route
          ├── admin.php
          ├── api.php
          ├── web.php
      ├── tests/
      ├── composer.json
      ├── module.json
      ├── README.md
      ├── LICENSE.md
```

### 2. 启动 `webman` 服务，并访问默认的应用模块URL

1. 启动 `webman`  服务
`php start.php start`
2. 使用浏览器访问 `/app/apple/index`（其中 `apple` 是你新建的模块名）
3. 看到: hello webman module 问候提示语则表示插件已成功运行

接下来和开发一个`webman`项目体验基本一致

## 配置说明

模块的配置文件都在模块根目录下的 `module.json` 文件内

```json
{
    "name": "apple", // 应用模块名
    "activity": true, // 是否启动该模块
    "author": "ifui", // 作者名
    "email": "", // 邮箱地址
    "homepage": "", // 模块主页地址
    "type": "", // 模块类型
    "version": "0.1.0", // 模块版本号
    "description": "", // 模块简介
    "keywords": [], // 模块关键词
    "route": {
        "autoRoute": true, // 是否开启自动路由
        "urlPrefix": "/app/apple" // 路由前缀
    }
}
```

## 命令介绍

### 模块命令

#### 1. 启动模块 module:start

```shell
php webman module:start apple
```

#### 2. 暂停模块 module:stop

```shell
php webman module:stop apple
```

#### 3. 查看模块列表 module:list

```shell
php webman module:list
```

```
+------------+--------+---------+--------+
| name       | author | version | status |
+------------+--------+---------+--------+
| apple      | ifui   | 0.1.0   | false  |
| blog       | ifui   | 0.1.0   | true   |
| peach      | ifui   | 0.1.0   | true   |
| watermelon | ifui   | 0.1.0   | false  |
+------------+--------+---------+--------+
```

### 4. 创建一个模块 module:create

```shell
php webman module:create
```

### 常用命令

> 其中 `--module` 亦可以不填，系统会提示选择对应的模块，输入对应的序号即可

#### 1. 创建一个模型文件 module:make-model

```shell
php webman module:make-model Post --module=apple
```

将会创建 `/plugin/apple/app/model/Post.php` 文件

#### 2. 创建一个控制器文件 module:make-controller

```shell
php webman module:make-controller Admin/Post --module=apple
```

将会创建 `/plugin/apple/app/controller/Admin/Post.php` 文件

#### 3. 创建一个中间件文件 make-middleware

```shell
php webman module:make-middleware LimitVisitMiddleware --module=apple
```

将会创建  `/plugin/apple/app/middleware/LimitVisitMiddleware.php` 文件

#### 4. 创建一个测试文件 module:make-test

```shell
php webman module:make-test unit/AppleTest --module=apple
```

将会创建  `/plugin/apple/tests/unit/AppleTest.php` 文件

### 数据库迁移命令

主要使用了数据库迁移工具 `Phinx`，

`Phinx` 项目地址：[https://github.com/cakephp/phinx](https://github.com/cakephp/phinx)

官方中文文档地址：[https://tsy12321.gitbooks.io/phinx-doc/content/](https://tsy12321.gitbooks.io/phinx-doc/content/)

使用数据库迁移命令前，请确保`webman` 根目录下的  `config/plugin/ifui/webman-module/phink.php` 配置是否正确

```php
<?php

return [
    "paths" => [
        "migrations" => "database/migrations",
        "seeds" => "database/seeds"
    ],
    "environments" => [
        "default_migration_table" => "phinxlog",
        "default_database" => "dev",
        "default_environment" => "dev",
        "dev" => [
            "adapter" => "DB_CONNECTION", // 修改此处 e.g. env('DB_CONNECTION')
            "host" => "DB_HOST", // 修改此处
            "name" => "DB_DATABASE", // 修改此处
            "user" => "DB_USERNAME", // 修改此处
            "pass" => "DB_PASSWORD", // 修改此处
            "port" => "DB_PORT", // 修改此处
            "charset" => "utf8"
        ]
    ]
];
```

#### 1. 创建一个迁移脚本 module:phink-create

迁移脚本命名应该保持 驼峰命名法

```shell
php webman module:phink-create CreatePost --module=apple
```

将会创建 `/plugin/apple/database/migrations/20220818084023_create_post.php`

#### 2. 执行迁移脚本 module:phink-migrate

Migrate 命令默认运行执行所有脚本，可选指定环境 `-e`，比如 `-e dev`

可以使用 `--target` 或者 `-t` 来指定执行某个迁移脚本

```shell
php webman module:phink-migrate --module=apple
```

#### 3. 回滚之前的迁移脚本 module:phink-rollback

```shell
php webman module:phink-rollback --module=apple
```

指定回滚环境 `-e dev`

```shell
php webman module:phink-rollback --module=apple -e dev
```

使用 `--target` 或者 `-t` 回滚指定版本迁移脚本，指定版本如果设置为0则回滚所有脚本

```shell
php webman module:phink-rollback --module=apple -t 20120103083322
```

可以使用 `--date` 或者 `-d` 参数回滚指定日期的脚本
 ```shell
 php webman module:phink-rollback --module=apple -d 2022
 php webman module:phink-rollback --module=apple -d 202208
 php webman module:phink-rollback --module=apple -d 20220816
 ```

#### 4. 创建数据填充文件 module:phink-seed-create

命名格式使用驼峰法

```shell
php webman module:phink-seed-create PostSeeder --module=apple
```

将会创建 `plugin/apple/database/seeds/PostSeeder.php` 文件

#### 5. 执行数据填充命令 module:phink-seed-run

默认 `module:phink-seed-run` 命令会执行所有 `seed`
```shell
php webman module:phink-seed-run --module=apple
```

如果你想要指定执行一个，只要增加 `-s` 参数后接 `seed` 的名字
```shell
php webman module:phink-seed-run --module=apple -s PostSeeder
```

## 使用说明

### 创建一个路由

在 `route` 文件夹下可以看到内置了 `admin.php` `api.php` `web.php` 

只要是在 `route` 文件夹下的 `php` 文件都会被执行

这里需要注意的是 `module.json` 中的 `urlPrefix` 参数将会影响这里，当 `urlPrefix` 为空时会影响全局路由。

比如在 `/plugin/apple/route/api.php`

```php
<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

use Webman\Route;

Route::get('/hello', function () {
    return response(config('plugin.apple.app.name'));
});
```

这时使用浏览器访问 `/app/apple/hello` 即可

### 使用配置

插件的配置与普通 `webman` 项目一样，不过插件的配置一般只对当前插件有效，对主项目一般无影响。

使用方式：`config('plugin.apple.app.name')`，其中 `apple` 为模块名，`app` 为 配置文件名

其他注意事项请参考官方文档：[https://www.workerman.net/doc/webman/plugin/app.html#配置文件](https://www.workerman.net/doc/webman/plugin/app.html#配置文件)

### 模块插件安装与卸载

应用插件安装时只需要将插件目录拷贝到`{主项目}/plugin`目录下即可，需要`reload`或`restart`才能生效。

卸载时直接删除`{主项目}/plugin`下对应的插件目录即可。

## 其他说明

该项目将紧随 `webman` 的更新而更新，其中肯定还有不少需要完善的地方，欢迎大家提 `PR`

有问题请联系邮箱： ifui@foxmail.com