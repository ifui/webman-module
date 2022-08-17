<?php
return [
    'enable' => true,

    /*
     |--------------------------------------------------------------------------
     | The module namespace
     | 模块的命名空间
     |--------------------------------------------------------------------------
     |
     | This is consistent with the webman plugin set, or can be set separately. e.g. module
     | 这里和官方的 plugin 保持一致，也可以单独设置，例如 module
     |
     */
    'namespace' => 'plugin',

    'paths' => [
        /*
        |--------------------------------------------------------------------------
        | Module folder Path
        | 模块应用文件夹路径
        |--------------------------------------------------------------------------
        |
        | This path used for save the generated Container.
        | 模块应用生成路径
        |
        */
        'module' => base_path() . DIRECTORY_SEPARATOR . 'plugin',

        /*
         |--------------------------------------------------------------------------
         | Stub Path
         | Stub 模板文件路径
         |--------------------------------------------------------------------------
         |
         | Customize path location.
         | 自定义模板的路径，你可以修改默认模板
         |
         */
        'stub_path' => base_path() . DIRECTORY_SEPARATOR . 'vendor/ifui/webman-module/stubs',

        /*
         |--------------------------------------------------------------------------
         | Module application folder generation path
         | 模块应用文件夹生成路径
         |--------------------------------------------------------------------------
         |
         | Customize the initialization directory structure.
         | 自定义的初始化文件目录
         |
         */
        'generator' => [
            'controller' => 'app/controller',
            'model' => 'app/model',
            'middleware' => 'app/middleware',
            'database-migrations' => 'database/migrations',
            'database-seeds' => 'database/seeds',
            'view' => 'view',
            'config' => 'config',
            'routes' => 'route',
            'tests' => 'tests'
        ],

        /*
         |--------------------------------------------------------------------------
         | Stub Path
         | 模板路径
         |--------------------------------------------------------------------------
         |
         | Customize the properties of the makefile.
         | 自定义模板的路径和对应关系
         |
         */
        'stub' => [
            'module.json' => ['from' => 'module.stub', 'to' => '/module.json'],
            'config-app' => ['from' => 'config/app.stub', 'to' => '/config/app.php'],
            'config-container' => ['from' => 'config/container.stub', 'to' => '/config/container.php'],
            'controller' => ['from' => 'controller/Index.stub', 'to' => '/app/controller/Index.php'],
            'provider' => ['from' => 'providers/AppServerProvider.stub', 'to' => '/app/providers/AppServerProvider.php'],
            'view' => ['from' => 'view/view.stub', 'to' => '/app/view/index/view.html'],
            'functions' => ['from' => 'functions.stub', 'to' => '/app/functions.php'],
            'route-api' => ['from' => 'route/api.stub', 'to' => '/route/api.php'],
            'route-admin' => ['from' => 'route/admin.stub', 'to' => '/route/admin.php'],
            'route-web' => ['from' => 'route/web.stub', 'to' => '/route/web.php'],
            'LICENSE' => ['from' => 'LICENSE.stub', 'to' => '/LICENSE.md'],
            'README' => ['from' => 'README.stub', 'to' => '/README.md'],
            'gitignore' => ['from' => 'gitignore.stub', 'to' => '/.gitignore.md'],
            'composer.json' => ['from' => 'composer.stub', 'to' => '/composer.json'],
            // tests
            'tests-TestCase' => ['from' => 'tests/TestCase.stub', 'to' => '/tests/TestCase.php'],
            'tests-CreateApplication' => ['from' => 'tests/CreateApplication.stub', 'to' => '/tests/CreateApplication.php'],
            'tests-unit-ExampleTest' => ['from' => 'tests/unit/ExampleTest.stub', 'to' => '/tests/unit/ExampleTest.php'],
        ],
    ]
];