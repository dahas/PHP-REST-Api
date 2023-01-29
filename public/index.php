<?php

use RESTapi\Sources\Api;
use RESTapi\Sources\Request;
use RESTapi\Sources\Response;

require dirname(__DIR__, 1) . '/vendor/autoload.php';

include("../settings.php");

$api = new Api();
$api->handle(new Request(), new Response());
