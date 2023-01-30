<?php

namespace RESTapi\Library;

use RESTapi\Sources\interfaces\ApiInterface;
use RESTapi\Library\Api;
use RESTapi\Sources\Request;
use RESTapi\Sources\Response;

class Authentication implements ApiInterface {

    public function __construct(private Api $api)
    {
    }


    public function handle(Request $request, Response $response): void
    {
        if (!SETTINGS["authentication"]["required"]) {
            $this->api->handle($request, $response);
            return;
        }

        $username = $request->getUsername();
        $password = $request->getPassword();

        if ($this->authenticate($username, $password)) {
            $this->api->handle($request, $response);
            return;
        }

        $json = json_encode([
            "status" => "error",
            "message" => "Access denied!"
        ]);

        $response->write($json);
        $response->setStatusCode(401);
        $response->flush();
    }


    /**
     * Verify credentials.
     * @return bool
     */
    private function authenticate(string $username, string $password): bool
    {
        /**
         * ToDo: Implement some authentication logic here ...
         */
        return $username == "rest" && $password == "test";
    }
}