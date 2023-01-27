<?php

define('SETTINGS', [
    
    "response_content_type" => "application/json",

    "db_type" => "mysql",

    "database" => [

        "mysql" => [

            "db_name" => "restapi",
            "host" => "localhost",
            "username" => "root",
            "password" => "password",
            "charset" => "utf8"
        ]
    ],

    "debug" => [

        "enabled" => true,

        "ip_whitelist" => [

            "192.168.10.1",
            "127.0.0.1",
            "::1"
        ]
    ]
]);
