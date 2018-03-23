<?php

// Which method do you want to test? 
// GET, POST, PUT or DELETE
$requestMethod = 'GET';


// Authetication:
$username = "localtest";
$password = "secret";


// Base URL:
$url = 'http://rest.test';

switch ($requestMethod) 
{
    case "GET":
        $url .= "/v1/Example/"; // List of items
        // $url .= "/v1/Example/3/"; // Single item with ID 3
        $ch = curl_init($url);
        break;

    case "POST":
        $postData = [
            "name" => "Greta Garbo",
            "age" => "93",
            "city" => "Hollywood",
            "country" => "California"
        ];
        $url .= "/v1/Example/";
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
        $url .= "/v1/Example/5/";
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
        break;

    case "DELETE":
        $url .= "/v1/Example/7/";
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        break;
}

curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
curl_setopt($ch, CURLOPT_HEADER, true);

if (! $output = curl_exec($ch)) {
    trigger_error(curl_error($ch));
}

curl_close($ch);
