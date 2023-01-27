<?php

namespace RESTapi\Sources;

use RESTapi\Sources\interfaces\WebServiceInterface;


abstract class WebService implements WebServiceInterface
{
    public function read(array $params = []) {}

    public function create(array $params) {}

    public function update(array $params) {}

    public function delete(array $params) {}
}
