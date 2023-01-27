<?php

namespace RESTapi\Sources;

class Router {
    private array $handlers;
    private $notFoundhandler;
    private $authorize;

    public function auth(mixed $callback)
    {
        $this->authorize = $callback;
    }

    public function get(string $path, mixed $callback): void
    {
        $this->addHandler("GET", $path, $callback);
    }

    public function post(string $path, mixed $callback): void
    {
        $this->addHandler("POST", $path, $callback);
    }

    public function put(string $path, mixed $callback): void
    {
        $this->addHandler("PUT", $path, $callback);
    }

    public function delete(string $path, mixed $callback): void
    {
        $this->addHandler("DELETE", $path, $callback);
    }

    private function addHandler(string $method, string $path, mixed $callback)
    {
        $this->handlers[$method . $path] = $callback;
    }

    public function notFoundHandler(mixed $callback)
    {
        $this->notFoundhandler = $callback;
    }

    public function handle(): void
    {
        $auth = $this->authorize;
        if(!$auth()) {
            header("HTTP/2.0 401 Unauthorized");
            echo "Access denied!";
            return;
        }
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = $_SERVER['REQUEST_URI'];
        $arrUri = parse_url($uri);
        $route = $arrUri['path'];

        if (!isset($this->handlers[$method . $route])) {
            header("HTTP/2.0 404 Not Found");
            $callback = $this->notFoundhandler;
        } else {
            $callback = $this->handlers[$method . $route];
        }

        $getVars = [];
        if (isset($arrUri['query']) && $arrUri['query']) {
            parse_str($arrUri['query'], $getVars);
        }

        call_user_func_array($callback, [
            array_merge($_POST, $getVars)
        ]);
    }
}