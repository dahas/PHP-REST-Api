<?php

namespace RESTapi\Sources;

use RESTapi\Sources\interfaces\RequestInterface;

class Request implements RequestInterface
{
    private string $method;
    private array $parameters;
    private string $version = "";
    private string $view = "";
    private int $identifier = 0;

    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'] ?? "GET";

        $uri = $_SERVER['REQUEST_URI'] ?? "";
        $this->parseUri($uri);

        $this->parseParameters();
    }

    private function parseParameters(): void
    {
        $rq = array_merge($_GET, $_POST);
        if ($this->method == "PUT" || $this->method == "PATCH") {
            parse_str(file_get_contents("php://input"), $_REQUEST);
            $rq = array_merge($rq, $_REQUEST);
        }
        $this->parameters = $rq;
    }

    public function getVersion(): string
    {
        return $this->version;
    }

    public function getView(): string
    {
        return $this->view;
    }

    public function getID(): int
    {
        return $this->identifier;
    }

    public function issetParameter($name): bool
    {
        return isset($this->parameters[$name]);
    }

    public function getParameter($name): string
    {
        if (isset($this->parameters[$name])) {
            return $this->filterInput($this->parameters[$name]);
        }
        return "";
    }

    public function getParameterNames(): array
    {
        return array_keys($this->parameters);
    }

    public function getHeader($name): string
    {
        $name = 'HTTP_' . strtoupper(str_replace('-', '_', $name));
        if (isset($_SERVER[$name])) {
            return $_SERVER[$name];
        }
        return "";
    }

    public function getMethod(): string
    {
        return $this->method;
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
            $this->identifier = intval($segments[2]);
        }

        $getVars = [];
        if (isset($arrUri['query']) && $arrUri['query']) {
            parse_str($arrUri['query'], $getVars);
        }
    }

    private function filterInput($input)
	{
		return filter_var(rawurldecode($input), FILTER_SANITIZE_STRING);
	}
}
