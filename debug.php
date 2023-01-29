<?php

use RESTapi\Sources\Api;
use RESTapi\Sources\Request;
use RESTapi\Sources\Response;

require 'vendor/autoload.php';

include("settings.php");

/************** METHOD: ***************/
$_SERVER['REQUEST_METHOD'] = 'GET'; # 'GET', 'POST', 'PUT', 'DELETE'

/*********** AUTHENTICATE: ************/
$_SERVER['PHP_AUTH_USER'] = 'rest';
$_SERVER['PHP_AUTH_PW'] = 'test';

/************* API CALL: **************/
$_SERVER['REQUEST_URI'] = "/v1/Users";

/************ POST DATA: **************/
$_POST['name'] = "John Rambo";
$_POST['age'] = 42;
$_POST['city'] = "L.A.";
$_POST['country'] = "California";


$api = new Api();
$api->verify(new Request(), new Response());
