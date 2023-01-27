<?php

namespace RESTapi\Sources\interfaces;

interface WebServiceInterface
{
    public function read(array $params = []);

    public function create(array $params);
    
    public function update(array $params);
    
    public function delete(array $params);
}
