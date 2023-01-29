<?php

namespace RESTapi\Sources;

use RESTapi\Sources\interfaces\ResponseInterface;

class Response implements ResponseInterface {

    private int $status = 200;
    private array $headers = [];
    private string $body = "";

    public function setStatusCode(int $status): void
    {
        $this->status = $status;
    }

    public function addHeader(string $name, mixed $value): void
    {
        $this->headers[$name] = $value;
    }

    public function write(string $content): void
    {
        $this->body .= $content;
    }

    public function flush(): void
    {
        header("HTTP/1.0 {$this->status}");

        foreach ($this->headers as $name => $value) {
            header("{$name}: {$value}");
        }

        print $this->body;

        $this->headers = [];
        $this->body = "";
    }
}
