<?php

use RESTapi\Sources\Api;
use RESTapi\Sources\Request;
use RESTapi\Sources\Response;

require '../vendor/autoload.php';

include("../settings.php");

if (!isset($_SERVER['PHP_AUTH_USER'])) {
    header('WWW-Authenticate: Basic realm="REST-Api"');
    header('HTTP/1.0 401 Unauthorized');
    echo 'You are not authorized to use this API.';
    exit;
}

$api = new Api();
$api->verify(new Request(), new Response());
