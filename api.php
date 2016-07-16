<?php

namespace php_rest;

function autoloader($class)
{
    $class = str_replace(__NAMESPACE__ . '\\', '', $class); // Windows
    $class = str_replace(__NAMESPACE__ . '/', '', $class); // Linux
    $class = str_replace("\\", "/", $class);
    require_once $class . '.php';
}

spl_autoload_register(__NAMESPACE__ . '\autoloader');

use php_rest\controller\FilesController;
use php_rest\controller\RequestController;
use php_rest\controller\ResponseController;
use php_rest\controller\OutputController;

$filesHandler = new FilesController();
$controller = new OutputController($filesHandler);

$controller->handleRequest(new RequestController(), new ResponseController());