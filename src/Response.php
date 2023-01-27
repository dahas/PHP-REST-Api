<?php

namespace RESTapi\Sources;

use RESTapi\Sources\interfaces\ResponseInterface;

class Response implements ResponseInterface
{
    private $status = 200;
    private $headers = array();
    private $body = null;

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function addHeader($name, $value)
    {
        $this->headers[$name] = $value;
    }

    public function write($data)
    {
        $this->body .= $data;
    }

    public function flush()
    {
        header("HTTP/1.0 {$this->status}");

        foreach ($this->headers as $name => $value) {
            header("{$name}: {$value}");
        }

        echo $this->body;

        $this->headers = array();
        $this->body = null;
    }
}
