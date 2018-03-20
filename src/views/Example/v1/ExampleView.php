<?php

namespace php_rest\src\views\Example\v1;

use php_rest\src\interfaces\ViewIF;
use php_rest\src\interfaces\ResponseIF;
use php_rest\src\interfaces\RequestIF;


class ExampleView implements ViewIF
{
    private $request;
    private $response;

    private $allowedRequestMethods = ["GET", "POST", "PUT", "DELETE"];

    // GET
    private function read($request=null)
    {
        echo json_encode(
            [
                "This" => "aaaaa",
                "Is" => "bbbbb",
                "Awesome" => "ccccc"
            ]
        );
        // To be done
        return true;
    }

    // POST
    private function create($request=null)
    {
        echo json_encode([
            "status" => "success",
            "data" => [
                "name" => $request->getParameter("name"),
                "age"  => $request->getParameter("age"),
                "city" => $request->getParameter("city"),
            ]
        ]);
        return true;
    }

    // PUT
    private function update($request=null)
    {
        echo json_encode([
            "status" => "success",
            "data" => [
                "name" => $request->getParameter("name"),
                "age"  => $request->getParameter("age"),
                "city" => $request->getParameter("city"),
            ]
        ]);
        return true;
    }

    // DELETE
    private function delete($request=null)
    {
        echo json_encode(["deleted" => "4 sure!"]);
        // ToDO
        return true;
    }

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
                if ($this->read($this->request))
                    $this->response->setStatus(200); // OK
                else
                    $this->response->setStatus(500); // Internal Server Error
                break;

            case "POST":
                if ($this->create($this->request))
                    $this->response->setStatus(201); // Created
                else
                    $this->response->setStatus(500); // Internal Server Error
                break;

            case "PUT":
                if ($this->update($this->request))
                    $this->response->setStatus(200); // OK
                else
                    $this->response->setStatus(500); // Internal Server Error
                break;

            case "DELETE":
                if ($this->delete($this->request))
                    $this->response->setStatus(200); // OK
                else
                    $this->response->setStatus(500); // Internal Server Error

                break;

            default:
                $this->response->setStatus(501); // Not Implemented
                return null;
                break;
        }
    }

    // Helper Functions

    /**
     * @return bool
     */
    private function validateAuthorization()
    {
        // ToDo: Get users auth data from DB
        $apiKey = 'localtest';
        $secret = 'secret';

        // ToDo: Token must be send as a request parameter
        $token = ($this->request->getParameter("token"));

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
        $sigArr = [];

        // Generate token for debugging:
        $whitelist = array('127.0.0.1', '::1');
        if((isset($GLOBALS["debug"]) && $GLOBALS["debug"]) || in_array($_SERVER['REMOTE_ADDR'], $whitelist)) {
            $sigArr[] = sha1($key . $secret . 'timestamp');
        }

        if ($secs < 1) $secs = 1;
        else if ($secs > 30) $secs = 30;

        $timestamp = gmdate("U");
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
