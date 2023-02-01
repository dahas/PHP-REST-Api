<?php

use RESTapi\Library\Authentication;
use RESTapi\Library\Controller;
use RESTapi\Sources\Request;
use RESTapi\Sources\Response;

require '../vendor/autoload.php';

include("../settings.php");

if (!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW'])) {
    header('WWW-Authenticate: Basic realm="REST-Api"');
    header('HTTP/2.0 401 Unauthorized');
    echo 'You are not authorized to use this API.';
    exit;
}

$request = new Request();
$response = new Response();
$controller = new Controller();
$auth = new Authentication($controller);
$auth->handle($request, $response);

$response->flush();
