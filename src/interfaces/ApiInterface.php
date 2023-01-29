<?php

namespace RESTapi\Sources\interfaces;

use RESTapi\Sources\Request;
use RESTapi\Sources\Response;

interface ApiInterface {

    /**
     * Verify user credentials if authentication mis required.
     * <hr>
     * @param Request $request The Request object
     * @param Response $response The Response object
     * @return void
     */
    public function verify(Request $request, Response $response): void;


    /**
     * Handle the Request, create the Service and return the Response.
     * <hr>
     * @param Request $request The Request object
     * @param Response $response The Response object
     * @return void
     */
    public function handle(Request $request, Response $response): void;
}
