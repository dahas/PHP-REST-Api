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

        $service = $this->loader->loadWebService($version, $view);
        $service->$method($request, $response);
        
        $response->flush();
    }
}