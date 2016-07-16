<?php

namespace php_rest\src\interfaces;

interface ViewIF
{
    public function execute(RequestIF $request, ResponseIF $response);
}

?>