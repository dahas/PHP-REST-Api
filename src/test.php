<?php

$requestMethod = "GET";

$url = "/api.php?version=v1&view=Example";

$data = array(
    "api_key" => "123test321",
    "token" => "keysecrettimestamp",
    "entry_id" => "",
    "module" => ""
);

switch ($requestMethod) {
    case "GET":
        $ch = curl_init($url . "?" . http_build_query($data));
        break;

    case "POST":
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        break;

    default:
        $data["version"] = "v1";
        $data["view"] = "Example";
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $requestMethod);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        break;
}

curl_setopt($ch, CURLOPT_HEADER, true);
curl_exec($ch);
curl_close($ch);