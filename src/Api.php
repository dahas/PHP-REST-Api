<?php declare(strict_types=1);

namespace RESTapi\Sources;

use RESTapi\Library\Authentication;
use RESTapi\Sources\interfaces\ApiInterface;

class Api implements ApiInterface {

    private Loader $loader;

    public function __construct()
    {
        $this->loader = new Loader();
    }


    public function verify(Request $request, Response $response): void
    {
        if (!SETTINGS["authentication"]["required"]) {
            $this->handle($request, $response);
            return;
        }

        $username = $request->getUsername();
        $password = $request->getPassword();

        $auth = new Authentication($username, $password);
        $isAuthorized = $auth(fn(bool $verified) => $verified);
        if ($isAuthorized) {
            $this->handle($request, $response);
        } else {
            $json = json_encode([
                "status" => "error",
                "message" => "Access denied!"
            ]);

            $response->write($json);
            $response->setStatusCode(401);
            $response->flush();
        }
    }


    public function handle(Request $request, Response $response): void
    {
        $method = $request->getMethod();
        $version = $request->getVersion();
        $service = $request->getService();

        if ($version && $service) {
            $service = $this->loader->loadWebService($version, $service);
            if ($service) {
                $service->$method($request, $response);
            } else {
                $json = json_encode([
                    "status" => "error",
                    "message" => "Service not found!"
                ]);

                $response->write($json);
                $response->setStatusCode(404);
            }
        } else {
            $json = json_encode([
                "status" => "error",
                "message" => "No valid URI!",
                "usage" => "domain.tld/[version]/[service]/[id]",
                "examples" => [
                    "collection" => "domain.tld/v1/Users",
                    "item" => "domain.tld/v1/Users/36"
                ]
            ]);

            $response->write($json);
            $response->setStatusCode(400);
        }

        $response->flush();
    }
}