<?php

namespace RESTapi\Sources\interfaces;

use RESTapi\Sources\WebService;

interface LoaderInterface {

    /**
     * Use this method to create an instance of a specific Service.
     * <hr>
     * @param string $version The version string. E.g.: "v2"
     * @param string $name The name of the Service.
     * @return WebService|null The Service, or null if class not found.
     */
    public function loadWebService(string $version, string $name): WebService|null;
}
