<?php

namespace php_rest\controller;

use php_rest\interfaces\ResponseIF;

class ResponseController implements ResponseIF
{
    private $status = 200;
    private $headers = array();
    private $content = null;

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function addHeader($name, $value)
    {
        $this->headers[$name] = $value;
    }

    public function send($data)
    {
        $this->content .= $data;
    }

    public function flush()
    {
        header("HTTP/1.0 {$this->status}");

        foreach ($this->headers as $name => $value) {
            header("{$name}: {$value}");
        }

        echo $this->content;

        $this->headers = array();
        $this->content = null;
    }
}
