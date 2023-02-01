<?php

namespace RESTapi\Library;

use RESTapi\Sources\interfaces\IMiddleware;
use RESTapi\Sources\Request;
use RESTapi\Sources\Response;

class Authentication implements IMiddleware {

    public function __construct(private IMiddleware $middleware)
    {
    }

    public function handle(Request $request, Response $response): void
    {
        if (!SETTINGS["authentication"]["required"]) {
            $this->middleware->handle($request, $response);
            return;
        }

        $username = $request->getUsername();
        $password = $request->getPassword();

        if ($this->verify($username, $password)) {
            $this->middleware->handle($request, $response);
            return;
        }

        $json = json_encode([
            "status" => "error",
            "message" => "Access denied!",
        ]);

        $response->write($json);
        $response->setStatusCode(401);
    }

    /**
     * Verify credentials.
     * @return bool
     */
    private function verify(string $username, string $password): bool
    {
        /**
         * ToDo: Implement some authentication logic here ...
         */
        return $username == "rest" && $password == "test";
    }
}