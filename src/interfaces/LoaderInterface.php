<?php

namespace RESTapi\Sources\interfaces;

use RESTapi\Sources\WebService;

interface LoaderInterface {

    public function loadWebService(string $version, string $name): WebService|null;
}