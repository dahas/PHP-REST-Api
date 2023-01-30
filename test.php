<?php

// Start webserver: php -S localhost:2400 -t public

// Set credentials:
$username = "rest";
$password = "test";

// Which method do you want to test? 
// GET, POST, PUT or DELETE
$requestMethod = 'GET';

// URL:
$url = 'http://localhost:2400/v1/Users/1';

switch ($requestMethod) {
    case "GET":
        $ch = curl_init($url);
        break;

    case "POST":
        $postData = [
            "name" => "Greta Garbo",
            "age" => "93",
            "city" => "Hollywood",
            "country" => "California"
        ];
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
        break;

    case "PUT":
        $postData = [
            "name" => "Jenny Piloso",
            "age" => "29",
            "city" => "Flagstaff",
            "country" => "Arizona"
        ];
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
        break;

    case "DELETE":
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        break;
}

curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
curl_setopt($ch, CURLOPT_HEADER, true);

if (!$output = curl_exec($ch)) {
    trigger_error(curl_error($ch));
}

curl_close($ch);
