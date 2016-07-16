<?php

namespace php_rest\interfaces;

interface RequestIF
{
    public function issetParameter($name);

    public function getParameter($name);

    public function getParameterNames();

    public function getHeader($name);

    public function getMethod();
}

?>