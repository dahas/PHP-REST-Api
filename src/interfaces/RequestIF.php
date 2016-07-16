<?php

namespace php_rest\src\interfaces;

interface RequestIF
{
    public function issetParameter($name);

    public function getParameter($name);

    public function getParameterNames();

    public function getHeader($name);

    public function getMethod();
}

?>