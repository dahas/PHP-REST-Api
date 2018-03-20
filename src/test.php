<?php

// URL for testing in external rest client (E.g. ARC):
// http://rest.local/v1/Example/?api_key=localtest&token=0acd0596ce9a6ed7fbcdff663b3be726e566ba36

$requestMethod = "GET";

$url = "http://rest.local/v1/Example/";

$apiKey = 'localtest';
$secret = 'secret';
$timestamp = 'timestamp'; // Create timestamp using: gmdate("U")

// Generate token:
// 0acd0596ce9a6ed7fbcdff663b3be726e566ba36
$token = sha1($apiKey . $secret . $timestamp);

$data = array(
    "api_key" => $apiKey,
    "token" => $token,
    "entry_id" => "",
    "module" => ""
);

switch ($requestMethod) {
    case "GET":
        $url .= "?" . http_build_query($data);
        $ch = curl_init($url);
        break;

    case "POST":
        $postData = [
            "name" => "Greta Garbo",
            "age" => "93",
            "city" => "Los Angeles"
        ];
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        break;

    default:
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $requestMethod);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        break;
}

curl_setopt($ch, CURLOPT_HEADER, true);

if (! $output = curl_exec($ch)) {
    trigger_error(curl_error($ch));
}

curl_close($ch);

// echo $output;
