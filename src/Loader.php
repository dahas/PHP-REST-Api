<?php declare(strict_types=1);

namespace RESTapi\Sources;

use RESTapi\Sources\interfaces\LoaderInterface;
use RESTapi\Sources\WebService;

class Loader implements LoaderInterface {

    public function loadWebService(string $version, string $name): WebService|null
    {
        $file = dirname(__DIR__) . "/lib/$version/$name.php";
        $class = "RESTapi\\Library\\$version\\$name";
        if (!file_exists($file)) {
            return null;
        }
        require_once $file;
        if (!class_exists($class)) {
            return null;
        }
        $service = new $class($this);
        if ($service instanceof WebService) {
            return $service;
        }
        return null;
    }
}