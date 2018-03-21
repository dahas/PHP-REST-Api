<?php
/*
+-------------------------------------------------+
|             TEST REST API WITH CURL             |
+-------------------------------------------------+
*/

// Which method do you want to test? 
// GET, POST, PUT or DELETE
$requestMethod = 'PUT';


// These values are for testing only.
// Usually a token is generated like this:
// sha1($apiKey . $secret . gmdate("U"));
$apiKey = 'localtest';
$secret = 'secret';
$timestamp = 'timestamp';
$token = '0acd0596ce9a6ed7fbcdff663b3be726e566ba36';
$uri = array("api_key" => $apiKey, "token" => $token);


// Base URL:
$url = 'http://rest.test';

switch ($requestMethod) 
{
    case "GET":
        $url .= "/v1/Example/"; // List of example items
        // $url .= "/v1/Example/3/"; // Single example item
        $url .= "?" . http_build_query($uri);
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
        $url .= "?" . http_build_query($uri);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
        break;

    case "PUT":
        $postData = [
            "name" => "Gretchen Garbo",
            "age" => "18",
            "city" => "Hollywood",
            "country" => "USA"
        ];
        $url .= "/v1/Example/6/";
        $url .= "?" . http_build_query($uri);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
        break;

    case "DELETE":
        $url .= "/v1/Example/7/";
        $url .= "?" . http_build_query($uri);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        break;
}

curl_setopt($ch, CURLOPT_HEADER, true);

if (! $output = curl_exec($ch)) {
    trigger_error(curl_error($ch));
}

curl_close($ch);

// echo $output;
