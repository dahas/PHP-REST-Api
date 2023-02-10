<?php

use RESTapi\Sources\Request;
use RESTapi\Sources\Response;
use RESTapi\Sources\WebService;


class Calculator extends WebService {

    public function multiply(Request $request, Response $response): void
    {
        $a = $request->getParameter("a");
        $b = $request->getParameter("b");

        $res = $a * $b;

        $json = json_encode([
            "status" => "success",
            "message" => "$a * $b = $res"
        ]);

        $response->write($json);
        $response->setStatus(200);
    }
}