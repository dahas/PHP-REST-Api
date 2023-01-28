<?php

use RESTapi\Sources\Api;
use RESTapi\Sources\Request;
use RESTapi\Sources\Response;

require __DIR__ . '/vendor/autoload.php';

include("settings.php");

/************** METHOD: ***************/
$_SERVER['REQUEST_METHOD'] = 'GET'; // 'GET', 'POST', 'PUT', 'DELETE'

/*********** AUTHENTICATE: ************/
$_SERVER['PHP_AUTH_USER'] = 'localtest';
$_SERVER['PHP_AUTH_PW'] = 'secret';

$_SERVER['REQUEST_URI'] = "/v1/Users/2";

/************ POST DATA: *************/
$_POST['name'] = "John Wayne";
$_POST['age'] = 77;
$_POST['city'] = "Rio Bravo";
$_POST['country'] = "Texas";

$api = new Api();
$api->handle(new Request(), new Response());
