<?php

namespace php_rest\controller;

use php_rest\interfaces\ResponseIF;
use php_rest\interfaces\RequestIF;
use php_rest\interfaces\FilesIF;

class OutputController
{
    private $filesHandler;

    public function __construct(FilesIF $fh)
    {
        $this->filesHandler = $fh;
    }

    public function handleRequest(RequestIF $request, ResponseIF $response)
    {
        if ($view = $this->filesHandler->getView($request)) {
            $view->execute($request, $response);
        } else {
            $response->setStatus(503);  // Service Unavailable
        }

        $response->flush();
    }
}
