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

    private $username;
    private $password;

    protected $allowedRequestMethods = [];
    protected $requiresAuthentication = true;

    public function authenticate() {}

    public function read($request=null, $response=null) {}

    public function create($request=null, $response=null) {}

    public function update($request=null, $response=null) {}

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

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return bool
     */
    private function validateAuthorization()
    {
        if (! $this->requiresAuthentication)
            return true;

        $this->authenticate();

        $userData = "{$this->username}:{$this->password}";

        if ($userData !== $this->getAuth())
            return false;

        return true;
    }

    /**
     * Getting the authorization http header of the basic authentication:
     */
    private function getAuth()
    {
        $username = $_SERVER["PHP_AUTH_USER"] ?? "";
        $password = isset($_SERVER["PHP_AUTH_PW"]) ? sha1($_SERVER["PHP_AUTH_PW"]) : "";
        return "$username:$password";
    }

    /**
     * @return string
     */
    private function getAllowedRequestMethodsString()
    {
        return implode(", ", $this->allowedRequestMethods);
    }
}
