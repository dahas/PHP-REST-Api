<?php

namespace RESTapi\Sources\interfaces;

interface ResponseInterface
{
    public function write($data);

    public function addHeader($name, $value);

    public function setStatus($status);

    public function flush();
}
