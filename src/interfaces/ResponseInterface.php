<?php

namespace RESTapi\Sources\interfaces;

interface ResponseInterface {

    /**
     * Adds content to the Response body.
     * <hr>
     * @param string $content Can be HTML as well as JSON or XML.
     * @return void
     */
    public function write(string $content): void;


    /**
     * Add a HTTP header to the response.
     * <hr>
     * @param string $name Header name
     * @param mixed $value Header value
     * @return void
     */
    public function addHeader(string $name, mixed $value): void;


    /**
     * The HTTP status code.
     * @param mixed $status Can be only the number or the full description. E.g.: "404 Not Found"
     * @return void
     */
    public function setStatus(int $status): void;


    /**
     * Print the response.
     * @return void
     */
    public function flush(): void;
}
