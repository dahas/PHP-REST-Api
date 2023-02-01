<?php

namespace RESTapi\Sources;

use RESTapi\Sources\Request;
use RESTapi\Sources\Response;
use RESTapi\Sources\interfaces\IWebservice;


abstract class WebService implements IWebservice {

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
