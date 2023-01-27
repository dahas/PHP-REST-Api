<?php

use RESTapi\Sources\Router;
use RESTapi\Library\v1\Users;

require __DIR__ . '/vendor/autoload.php';

include("settings.php");

$router = new Router();

$router->auth(function () {
    /**
     * Implement authentication logic here. Return 
     * either true, if authentication is verified, 
     * or false, if not.
     */
    return true; 
});

$router->get("/v1/Users", function ($params) {
    (new Users())->read($params);
});

$router->post("/v1/Users", function ($params) {
    (new Users())->create($params);
});

$router->put("/v1/Users", function ($params) {
    (new Users())->update($params);
});

$router->delete("/v1/Users", function ($params) {
    (new Users())->delete($params);
});

$router->notFoundHandler(function () {
    echo "404 Not Found";
});

$router->handle();
