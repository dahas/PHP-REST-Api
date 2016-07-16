<?php

namespace php_rest\interfaces;

interface ResponseIF
{
    public function send($data);

    public function addHeader($name, $value);

    public function setStatus($status);

    public function flush();
}

?>