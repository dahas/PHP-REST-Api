<?php

namespace RESTapi\Sources;

use RESTapi\Sources\interfaces\IResponse;

class Response implements IResponse {

    private int $status = 200;
    private array $headers = [];
    private string $body = "";

    public function __construct()
    {
        $this->headers["Content-Type"] = SETTINGS["response_content_type"];
    }

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
        header("HTTP/2.0 {$this->status}");

        foreach ($this->headers as $name => $value) {
            header("{$name}: {$value}");
        }

        print $this->body;

        $this->headers = [];
        $this->body = "";
    }
}