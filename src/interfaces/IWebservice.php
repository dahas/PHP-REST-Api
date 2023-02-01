<?php

namespace RESTapi\Sources\interfaces;

use RESTapi\Sources\Request;
use RESTapi\Sources\Response;

interface IWebservice {

    /**
     * Retrieve a collection:
     * `GET domain.tld/[version]/[service]`
     * Retrieve a single item:
     * `GET domain.tld/[version]/[service]/[id]`
     * <hr>
     * @param Request $request The Request Object
     * @param Response $response The Response Object
     */
    public function get(Request $request, Response $response);


    /**
     * Add a new item to the collection:
     * `POST domain.tld/[version]/[service]`
     * <hr>
     * @param Request $request The Request Object
     * @param Response $response The Response Object
     */
    public function post(Request $request, Response $response);


    /**
     * Update an existing item:
     * `PUT domain.tld/[version]/[service]/[id]`
     * <hr>
     * @param Request $request The Request Object
     * @param Response $response The Response Object
     */
    public function put(Request $request, Response $response);


    /**
     * Delete an existing item:
     * `DELETE domain.tld/[version]/[service]/[id]`
     * <hr>
     * @param Request $request The Request Object
     * @param Response $response The Response Object
     */
    public function delete(Request $request, Response $response);
}
