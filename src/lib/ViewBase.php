<?php

namespace php_rest\src\lib;

use php_rest\src\interfaces\ViewIF;
use php_rest\src\interfaces\ResponseIF;
use php_rest\src\interfaces\RequestIF;

/**
 * Class ViewBase
 * @package php_rest\src\lib
 */
abstract class ViewBase implements ViewIF
{
    private $request;
    private $response;

    protected $allowedRequestMethods = [];

    // GET
    public function read($request=null, $response=null) {}

    // POST
    public function create($request=null, $response=null) {}

    // PUT
    public function update($request=null, $response=null) {}

    // DELETE
    public function delete($request=null, $response=null) {}

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
        $this->response->addHeader("Content-Type", $GLOBALS["response_content_type"]);

        switch ($request->getMethod()) 
        {
            case "GET":
                if ($this->read($this->request, $this->response))
                    $this->response->setStatus(200); // OK
                else
                    $this->response->setStatus(500); // Internal Server Error
                break;

            case "POST":
                if ($this->create($this->request, $this->response))
                    $this->response->setStatus(201); // Created
                else
                    $this->response->setStatus(500); // Internal Server Error
                break;

            case "PUT":
                if ($this->update($this->request, $this->response))
                    $this->response->setStatus(200); // OK
                else
                    $this->response->setStatus(500); // Internal Server Error
                break;

            case "DELETE":
                if ($this->delete($this->request, $this->response))
                    $this->response->setStatus(200); // OK
                else
                    $this->response->setStatus(500); // Internal Server Error
                break;

            default:
                $this->response->setStatus(501); // Not Implemented
                return null;
        }
    }

    // Helper Functions

    /**
     * @return bool
     */
    private function validateAuthorization()
    {
        // ToDo: Get users auth data from OAuth2
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
     * @param string $key  The api key
     * @param string $secret The secret string
     * @param int $secs Amount of seconds a token is valid (default 2, min 1 - max 30 seconds)
     * @return array Array of hashed tokens
     */
    private function createAccessTokens($key, $secret, $secs = 2)
    {
        $sigArr = [];

        // Generate token for debugging:
        $whitelist = $GLOBALS["debug"]["ip_whitelist"];
        if((isset($GLOBALS["debug"]["enabled"]) && $GLOBALS["debug"]["enabled"]) || (isset($_SERVER['REMOTE_ADDR']) && in_array($_SERVER['REMOTE_ADDR'], $whitelist))) {
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
