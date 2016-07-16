<?php

namespace php_rest\views\Example\v1;

use php_rest\interfaces\ViewIF;
use php_rest\interfaces\ResponseIF;
use php_rest\interfaces\RequestIF;


class ExampleView implements ViewIF
{
    private $request;
    private $response;

    private $allowedRequestMethods = ["GET", "POST", "PUT", "DELETE"];

    /**
     * @param RequestIF $request The Request Handler
     * @param ResponseIF $response The Response Handler
     * @return null
     */
    public function execute(RequestIF $request, ResponseIF $response)
    {
        $this->request = $request;
        $this->response = $response;

        // Validate required request parameters
        if (!$this->request->issetParameter("api_key") || !$this->request->issetParameter("token")) {
            $this->response->setStatus(400); // Bad Request
            return null;
        }

        if (!$this->validateAuthorization()) {
            $this->response->setStatus(401); // Unauthorized
            return null;
        }

        if (!in_array($request->getMethod(), $this->allowedRequestMethods)) {
            $this->response->setStatus(405); // Method Not Allowed
            return null;
        }

        $this->response->addHeader("Allow", $this->getAllowedRequestMethodsString());
        $this->response->addHeader("Content-Type", "application/json");

        switch ($request->getMethod()) {
            case "GET":
                if ($this->read())
                    $this->response->setStatus(200); // OK
                else
                    $response->setStatus(500); // Internal Server Error
                break;

            case "POST":
                if ($this->create())
                    $this->response->setStatus(201); // Created
                else
                    $response->setStatus(500); // Internal Server Error
                break;

            case "PUT":
                if ($this->update())
                    $this->response->setStatus(200); // OK
                else
                    $response->setStatus(500); // Internal Server Error
                break;

            case "DELETE":
                if ($this->delete())
                    $this->response->setStatus(200); // OK
                else
                    $response->setStatus(500); // Internal Server Error

                break;

            default:
                $this->response->setStatus(501); // Not Implemented
                return null;
                break;
        }
    }

    // GET
    private function read()
    {
        // ToDO
        return true;
    }

    // POST
    private function create()
    {
        // ToDO
        return true;
    }

    // PUT
    private function update()
    {
        // ToDO
        return true;
    }

    // DELETE
    private function delete()
    {
        // ToDO
        return true;
    }

    // Helper Functions

    /**
     * @return bool
     */
    private function validateAuthorization()
    {
        // ToDo: Get users auth data from DB
        $apiKey = '123test321';
        $secret = 'secret';

        // ToDo: Token must be send as a request parameter
        $token = sha1($apiKey . $secret . (gmdate("U"))); #sha1($this->request->getParameter("token"));

        $accTokens = $this->createAccessTokens($apiKey, $secret);

        if ($this->request->getParameter("api_key") != $apiKey || !in_array($token, $accTokens))
            return false;

        return true;
    }

    /**
     * @param $key      The api key
     * @param $secret   The secret string
     * @param int $secs Amount of seconds a token is valid (default 2, min 1 - max 30 seconds)
     * @return array    Array of hashed tokens
     */
    private function createAccessTokens($key, $secret, $secs = 2)
    {
        if ($secs < 1) $secs = 1;
        else if ($secs > 30) $secs = 30;

        $timestamp = gmdate("U");
        $sigArr = [];
        for ($ts = $timestamp; $ts > $timestamp - $secs; $ts--) {
            $sigArr[] = sha1($key . $secret . $ts);
        }

        return $sigArr;
    }

    /**
     * @return string
     */
    private function getAllowedRequestMethodsString()
    {
        return implode(", ", $this->allowedRequestMethods);
    }
}
