<?php

namespace RESTapi\Library;

class Authentication {

    public function __construct(public string $username, public string $password)
    {
    }


    public function __invoke(callable $next)
    {
        $verified = $this->authenticate();
        return $next($verified);
    }


    /**
     * Verify credentials.
     * @return bool
     */
    private function authenticate(): bool
    {
        /**
         * ToDo: Implement your authentication logic here ...
         */
        return $this->username == "rest" && $this->password == "test";
    }
}
