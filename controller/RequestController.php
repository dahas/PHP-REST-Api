<?php

namespace php_rest\controller;

use php_rest\interfaces\RequestIF;

class RequestController implements RequestIF
{
    private $parameters;

    public function __construct()
    {
        if ($this->getMethod() != "GET" && $this->getMethod() != "POST")
            parse_str(file_get_contents("php://input"), $_REQUEST);
        $this->parameters = $_REQUEST;
    }

    public function issetParameter($name)
    {
        return isset($this->parameters[$name]);
    }

    public function getParameter($name)
    {
        if (isset($this->parameters[$name])) {
            return $this->parameters[$name];
        }
        return null;
    }

    public function getParameterNames()
    {
        return array_keys($this->parameters);
    }

    public function getHeader($name)
    {
        $name = 'HTTP_' . strtoupper(str_replace('-', '_', $name));
        if (isset($_SERVER[$name])) {
            return $_SERVER[$name];
        }
        return null;
    }

    public function getMethod()
    {
        return $_SERVER['REQUEST_METHOD'];
    }
}
