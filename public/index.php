<?php

use RESTapi\Library\Authentication;
use RESTapi\Sources\Api;
use RESTapi\Sources\Request;
use RESTapi\Sources\Response;

require '../vendor/autoload.php';

include("../settings.php");

if (!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW'])) {
    header('WWW-Authenticate: Basic realm="REST-Api"');
    header('HTTP/1.0 401 Unauthorized');
    echo 'You are not authorized to use this API.';
    exit;
}

$api = new Api();
$auth = new Authentication($api);
$auth->handle(new Request(), new Response());
