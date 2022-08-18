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
            "adapter" => "DB_CONNECTION",
            "host" => "DB_HOST",
            "name" => "DB_DATABASE",
            "user" => "DB_USERNAME",
            "pass" => "DB_PASSWORD",
            "port" => "DB_PORT",
            "charset" => "utf8"
        ]
    ]
];