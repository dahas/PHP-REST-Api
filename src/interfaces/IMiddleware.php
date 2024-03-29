<?php

namespace RESTapi\Sources\interfaces;

use RESTapi\Sources\Request;
use RESTapi\Sources\Response;

interface IMiddleware
{
    /**
     * Handle the Request and return the Response.
     * <hr>
     * @param Request $request The Request object
     * @param Response $response The Response object
     * @return void
     */
    public function handle(Request $request, Response $response): void;
}
