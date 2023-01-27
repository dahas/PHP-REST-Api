<?php

namespace RESTapi\Sources;

use RESTapi\Sources\interfaces\RequestInterface;

class Request implements RequestInterface
{
    private array $parameters;
    private string $version = "";
    private string $view = "";
    private string $identifier = "";

    public function __construct()
    {
        $rq = $_REQUEST;
        if ($this->getMethod() == "PUT" || $this->getMethod() == "PATCH") {
            parse_str(file_get_contents("php://input"), $_REQUEST);
            $rq = array_merge($rq, $_REQUEST); // Merging GET and PUT/PATCH data
        }
        
        $this->parameters = $rq;
        $this->parseUri($_SERVER['REQUEST_URI']);
    }

    private function parseUri(string $uri): void
    {
        $arrUri = parse_url($uri);
        $route = $arrUri['path'];
        $segments = explode("/", substr($route, 1));

        if (isset($segments[0])) {
            $this->version = $segments[0];
        }
        if (isset($segments[1])) {
            $this->view = $segments[1];
        }
        if (isset($segments[2])) {
            $this->identifier = $segments[2];
        }

        $getVars = [];
        if (isset($arrUri['query']) && $arrUri['query']) {
            parse_str($arrUri['query'], $getVars);
        }
    }

    public function version(): string
    {
        return $this->version;
    }

    public function view(): string
    {
        return $this->view;
    }

    public function identifier(): string
    {
        return $this->identifier;
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
