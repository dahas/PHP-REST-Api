<?php

namespace php_rest;


$GLOBALS["debug"] = true;

// Simulate method:
$_SERVER['REQUEST_METHOD'] = 'GET';

$_REQUEST['version'] = 'v1';
$_REQUEST['view'] = 'Example';
// $_REQUEST['uid'] = '2';
$_REQUEST['api_key'] = 'localtest';
$_REQUEST['token'] = '0acd0596ce9a6ed7fbcdff663b3be726e566ba36'; // Generate like this: sha1($apiKey . $secret . (gmdate("U")))

// Sample data:
$_REQUEST['name'] = "Jason Whittaker";
$_REQUEST['age'] = 36;
$_REQUEST['city'] = "Capetown";
$_REQUEST['country'] = "Southafrica";


function autoloader($class)
{
    $class = str_replace(__NAMESPACE__ . '\\', '', $class); // Windows
    $class = str_replace(__NAMESPACE__ . '/', '', $class); // Linux
    $class = str_replace("\\", "/", $class);
    require_once $class . '.php';
}

spl_autoload_register(__NAMESPACE__ . '\autoloader');

use php_rest\src\controller\FilesController;
use php_rest\src\controller\RequestController;
use php_rest\src\controller\ResponseController;
use php_rest\src\controller\OutputController;

$filesHandler = new FilesController();
$controller = new OutputController($filesHandler);

$controller->handleRequest(new RequestController(), new ResponseController());
