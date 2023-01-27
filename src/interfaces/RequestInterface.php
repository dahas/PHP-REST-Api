<?php

namespace RESTapi\Sources\interfaces;

interface RequestInterface
{
    public function issetParameter($name);

    public function getParameter($name);

    public function getParameterNames();

    public function getHeader($name);

    public function getMethod();
}
