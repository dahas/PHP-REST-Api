<?php

namespace RESTapi\Sources\interfaces;

use RESTapi\Sources\Request;
use RESTapi\Sources\Response;

interface WebServiceInterface
{
    public function get(Request $request, Response $response);

    public function post(Request $request, Response $response);
    
    public function put(Request $request, Response $response);
    
    public function delete(Request $request, Response $response);
}
