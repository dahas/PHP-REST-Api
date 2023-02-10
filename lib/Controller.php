<?php declare(strict_types=1);

namespace RESTapi\Library;

use RESTapi\Sources\interfaces\IMiddleware;
use RESTapi\Sources\Loader;
use RESTapi\Sources\Request;
use RESTapi\Sources\Response;

class Controller implements IMiddleware {

    private Loader $loader;

    public function __construct()
    {
        $this->loader = new Loader();
    }

    public function handle(Request $request, Response $response): void
    {
        $func = $request->getAction() ? $request->getAction() : $request->getMethod();
        $version = $request->getVersion();
        $service = $request->getService();

        if ($version && $service) {
            $service = $this->loader->loadWebService($version, $service);
            if ($service) {
                $service->$func($request, $response);
            } else {
                $json = json_encode([
                    "status" => "error",
                    "message" => "Service not found!"
                ]);

                $response->write($json);
                $response->setStatus(404);
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
            $response->setStatus(400);
        }
    }
}