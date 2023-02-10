<?php

// Start webserver: php -S localhost:2400 -t public

// Set credentials:
$username = "rest";
$password = "test";

// Which method do you want to test? 
// GET, POST, PUT or DELETE
$requestMethod = 'PATCH';

// URL:
$url = 'http://localhost:2400/v1/Users';

switch ($requestMethod) {

    case "GET":
        $ch = curl_init($url);
        break;

    case "POST":
        $body = [
            "name" => "Greta Garbo",
            "age" => "93",
            "city" => "Los Angeles",
            "country" => "California"
        ];
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($body));
        break;

    case "PUT":
        $body = [
            "name" => "John T. Piloso",
            "age" => "29",
            "city" => "Flagstaff",
            "country" => "Arizona"
        ];
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($body));
        break;

    case "DELETE":
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        break;

    case "PATCH":
        $url = 'http://localhost:2400/v1/Calculator/multiply';
        $body = [
            "a" => 12,
            "b" => 2.5
        ];
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PATCH");
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($body));
        break;
}

curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
curl_setopt($ch, CURLOPT_HEADER, true);

if (!$output = curl_exec($ch)) {
    trigger_error(curl_error($ch));
}

curl_close($ch);