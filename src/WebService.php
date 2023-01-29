<?php

namespace RESTapi\Sources;

use RESTapi\Sources\Request;
use RESTapi\Sources\Response;
use RESTapi\Sources\interfaces\WebServiceInterface;


abstract class WebService implements WebServiceInterface {

    public function get(Request $request, Response $response)
    {
    }

    public function post(Request $request, Response $response)
    {
    }

    public function put(Request $request, Response $response)
    {
    }

    public function delete(Request $request, Response $response)
    {
    }
}
