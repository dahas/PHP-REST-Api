<?php declare(strict_types=1);

namespace RESTapi\Sources;

use RESTapi\Sources\interfaces\LoaderInterface;
use RESTapi\Sources\WebService;

final class Loader implements LoaderInterface {

    public function loadWebService(string $version, string $name): WebService|null
    {
        $file = dirname(__DIR__) . "/services/$version/$name.php";
        if (!file_exists($file)) {
            return null;
        }
        require_once $file;
        if (!class_exists($name)) {
            return null;
        }
        $service = new $name($this);
        if ($service instanceof WebService) {
            return $service;
        }
        return null;
    }
}