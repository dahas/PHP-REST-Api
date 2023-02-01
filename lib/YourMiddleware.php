<?php declare(strict_types=1);

namespace RESTapi\Library;

use RESTapi\Sources\interfaces\IMiddleware;
use RESTapi\Sources\Request;
use RESTapi\Sources\Response;

class YourMiddleware implements IMiddleware {

    public function __construct() {}

    public function handle(Request $request, Response $response): void
    {
        $response->write("Hello from your Middleware!");
        $response->flush();
    }
}