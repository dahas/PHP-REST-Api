<?php

namespace RESTapi\Sources\interfaces;

interface RequestInterface {

    /**
     * Returns the version part of the request URI.
     * @return string
     */
    public function getVersion(): string;


    /**
     * Returns the name of the requested Service.
     * @return string
     */
    public function getView(): string;


    /**
     * Returns the unique identifier (UID) of a resource collection.
     * @return int
     */
    public function getID(): int;


    /**
     * Check if the parameter exists.
     * <hr>
     * @param string $name Name of the parameter
     * @return bool
     */
    public function issetParameter(string $name): bool;


    /**
     * Get the value of a parameter.
     * <hr>
     * @param string $name Name of the parameter
     * @return string
     */
    public function getParameter(string $name): string;


    /**
     * Retrieve a list of all available parameters
     * <hr>
     * @return array
     */
    public function getParameterNames(): array;


    /**
     * Get a specific HTTP header
     * <hr>
     * @param string $name Name of the header
     * @return string
     */
    public function getHeader(string $name): string;


    /**
     * The HTTP request method: GET, POST, PUT, PATCH, DELETE, ...
     * <hr>
     * @return string
     */
    public function getMethod(): string;
}
