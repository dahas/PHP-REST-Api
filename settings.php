<?php

$GLOBALS = [
    "response_content_type" => "application/json",

    "db_type" => "mysql",

    "database" => [
        "mysql" => [
            "db_name" => "restapi",
            "host" => "192.168.10.10",
            "username" => "homestead",
            "password" => "secret",
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

];
