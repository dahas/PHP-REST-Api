<?php

namespace php_rest\src\controller;

use php_rest\src\interfaces\RequestIF;

class RequestController implements RequestIF
{
    private $parameters;

    public function __construct()
    {
        $rq = $_REQUEST;
        if ($this->getMethod() == "PUT" || $this->getMethod() == "PATCH") {
            parse_str(file_get_contents("php://input"), $_REQUEST);
            $rq = array_merge($rq, $_REQUEST); // Merging GET and PUT/PATCH data
        }
        
        $this->parameters = $rq;
    }

    public function issetParameter($name)
    {
        return isset($this->parameters[$name]);
    }

    public function getParameter($name)
    {
        if (isset($this->parameters[$name])) {
            return $this->filterInput($this->parameters[$name]);
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

    private function filterInput($input)
	{
		return filter_var(rawurldecode($input), FILTER_SANITIZE_STRING);
	}
}
