<?php

namespace php_rest\src\interfaces;

interface ViewIF
{
    public function read(RequestIF $request, ResponseIF $response);

    public function create(RequestIF $request, ResponseIF $response);
    
    public function update(RequestIF $request, ResponseIF $response);
    
    public function delete(RequestIF $request, ResponseIF $response);

    public function execute(RequestIF $request, ResponseIF $response);
}

?>