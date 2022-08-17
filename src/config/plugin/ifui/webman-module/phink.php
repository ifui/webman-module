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
            "adapter" => env('DB_CONNECTION'),
            "host" => env('DB_HOST'),
            "name" => env('DB_DATABASE'),
            "user" => env('DB_USERNAME'),
            "pass" => env('DB_PASSWORD'),
            "port" => env('DB_PORT'),
            "charset" => "utf8"
        ]
    ]
];