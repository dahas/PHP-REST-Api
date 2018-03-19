<?php

error_reporting(E_ALL);

// $url = "http://rest.local/curl_test.php";
// $ch = curl_init($url);
// curl_setopt($ch, CURLOPT_PORT , 8888);
// curl_setopt($ch, CURLOPT_HEADER, true);
// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// $output = curl_exec($ch);
// curl_close($ch);
// echo $output;


# An HTTP GET request example

$url = 'http://rest.local/curl_test.php';
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_PORT , 8888);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_TIMEOUT, 5);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$data = curl_exec($ch);
curl_close($ch);
echo $data;
