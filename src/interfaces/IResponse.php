<?php

namespace RESTapi\Sources\interfaces;

interface IResponse {

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
     * <hr>
     * @param int $status The number of the HTTP Status.
     * @return void
     */
    public function setStatusCode(int $status): void;


    /**
     * Print the response.
     * @return void
     */
    public function flush(): void;
}
