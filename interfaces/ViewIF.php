<?php

namespace php_rest\interfaces;

interface ViewIF
{
    public function execute(RequestIF $request, ResponseIF $response);
}

?>