<?php declare(strict_types=1);

namespace RESTapi\Sources;

use RESTapi\Sources\interfaces\ApiInterface;

class Api implements ApiInterface {

    private Loader $loader;

    public function __construct()
    {
        $this->loader = new Loader();
    }

    public function handle(Request $request, Response $response): void
    {
        $method = $request->getMethod();
        $version = $request->getVersion();
        $view = $request->getView();

        if ($version && $view) {
            $service = $this->loader->loadWebService($version, $view);
            $service->$method($request, $response);
        } else {
            $json = json_encode([
                "status" => "error",
                "message" => "No URI!",
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