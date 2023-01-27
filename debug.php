<?php

use RESTapi\Sources\Router;
use RESTapi\Library\v1\Users;

require __DIR__ . '/vendor/autoload.php';

include("settings.php");

/************** METHOD: ***************/
$_SERVER['REQUEST_METHOD'] = 'GET'; // 'GET', 'POST', 'PUT', 'DELETE'

/*********** AUTHENTICATE: ************/
$_SERVER['PHP_AUTH_USER'] = 'localtest';
$_SERVER['PHP_AUTH_PW'] = 'secret';

$_SERVER['REQUEST_URI'] = "/v1/Users";

/************ POST DATA: *************/
$_POST['name'] = "Roger Whittaker";
$_POST['age'] = 88;
$_POST['city'] = "Rio Bravo";
$_POST['country'] = "USA";

$router = new Router();

$router->auth(function () {
    return true; // Return false, if not logged in.
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
