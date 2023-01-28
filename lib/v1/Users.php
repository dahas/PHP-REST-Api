<?php

namespace RESTapi\Library\v1;

use RESTapi\Sources\Request;
use RESTapi\Sources\Response;
use RESTapi\Sources\WebService;
use RESTapi\Sources\Database;


class Users extends WebService {

    private Database $db;


    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Retrieve a collection:
     * GET api.domain.tld/[version]/[library]
     * Retrieve a single item:
     * GET api.domain.tld/[version]/[library]/[id]
     * @param Request $request The Request Object
     * @param Response $response The Response Object
     */
    public function get(Request $request, Response $response): void
    {
        if (!$this->db->dbCheck()) {
            $json = json_encode($this->db->dbInfo());
            $response->write($json);
            return;
        }

        $query = [];
        $query["columns"] = "uid, name, age, city, country";
        $query["from"] = "sampledata";

        if ($request->getID())
            $query["where"] = "uid=" . $request->getID();

        $recordset = $this->db->select($query);

        if (!$recordset) {
            $json = json_encode([
                "status" => "error",
                "message" => "Query could not be executed."
            ]);
            $response->write($json);
            return;
        }

        $affectedRows = $recordset->getRecordCount();

        $users = [];
        while ($record = $recordset->next()) {
            $users[] = $record;
        }

        if ($affectedRows == 1) {
            $json = json_encode([
                "status" => "success",
                "data" => [
                    "user" => $users[0]
                ]
            ]);
            $response->write($json);
        } else if ($affectedRows > 1) {
            $json = json_encode([
                "status" => "success",
                "data" => [
                    "users" => $users
                ]
            ]);
            $response->write($json);
        } else {
            $json = json_encode([
                "status" => "fail",
                "message" => "No record found."
            ]);
            $response->write($json);
        }

        $response->addHeader("X-Data-Count", $affectedRows);
    }

    /**
     * Add a new item to the collection:
     * POST api.domain.tld/[version]/[library]
     * @param Request $request The Request Object
     * @param Response $response The Response Object
     */
    public function post(Request $request, Response $response): void
    {
        $this->db = Database::getInstance();
        if (!$this->db->dbCheck()) {
            $json = json_encode($this->db->dbInfo());
            $response->write($json);
            return;
        }

        $data = [
            $request->getParameter("name"),
            $request->getParameter("age"),
            $request->getParameter("city"),
            $request->getParameter("country")
        ];

        // Quote values:
        array_walk($data, function (&$item) {
            $item = "$item";
        });

        $query = [];
        $query["into"] = "sampledata";
        $query["columns"] = "name, age, city, country";
        $query["values"] = implode(", ", $data);

        $uid = $this->db->insert($query);

        if (!$uid) {
            $json = json_encode([
                "status" => "error",
                "message" => "Query could not be executed."
            ]);
            $response->write($json);
            return;
        }

        $json = json_encode([
            "status" => "success",
            "insert_id" => $uid,
            "data" => $data
        ]);
        $response->write($json);

        $response->addHeader("X-Insert-Count", 1);
    }

    /**
     * Update an existing item:
     * PUT api.domain.tld/[version]/[library]/[id]
     * @param Request $request The Request Object
     * @param Response $response The Response Object
     */
    public function put(Request $request, Response $response): void
    {
        $this->db = Database::getInstance();
        if (!$this->db->dbCheck()) {
            $json = json_encode($this->db->dbInfo());
            $response->write($json);
            return;
        }

        if (!$request->getID()) {
            $json = json_encode([
                "status" => "error",
                "message" => "Please provide a unique ID."
            ]);
            $response->write($json);
            return;
        }

        $data = [];
        $fields = ["name", "age", "city", "country"];

        foreach ($fields as $field) {
            if ($request->getParameter($field)) {
                $data[$field] = $request->getParameter($field);
            }
        }

        // Prepare values:
        array_walk($data, function (&$item, $key) {
            $item = "$key='$item'";
        });

        $dataStr = implode(", ", $data);

        $query = [];
        $query["table"] = "sampledata";
        $query["set"] = $dataStr;
        $query["where"] = "uid=" . $request->getID();

        $affectedRows = $this->db->update($query);

        if ($affectedRows > 0) {
            $json = json_encode([
                "status" => "success",
                "affected_rows" => $affectedRows,
                "update_id" => $request->getID(),
                "data" => $data
            ]);
            $response->write($json);
        } else if ($affectedRows < 0) {
            $json = json_encode([
                "status" => "fail",
                "message" => "Query could not be executed."
            ]);
            $response->write($json);
        } else {
            $json = json_encode([
                "status" => "fail",
                "message" => "Either no changes detected or the target record does not exist."
            ]);
            $response->write($json);
        }

        $response->addHeader("X-Update-Count", $affectedRows);
    }

    /**
     * Delete an existing item:
     * DELETE api.domain.tld/[version]/[library]/[id]
     * @param Request $request The Request Object
     * @param Response $response The Response Object
     */
    public function delete(Request $request, Response $response): void
    {
        $this->db = Database::getInstance();
        if (!$this->db->dbCheck()) {
            $json = json_encode($this->db->dbInfo());
            $response->write($json);
            return;
        }

        if (!$request->getID()) {
            $json = json_encode([
                "status" => "error",
                "message" => "Please provide a unique ID."
            ]);
            $response->write($json);
            return;
        }

        $query = [];
        $query["from"] = "sampledata";
        $query["where"] = "uid=" . $request->getID();

        $affectedRows = $this->db->delete($query);

        if ($affectedRows) {
            $json = json_encode([
                "status" => "success",
                "affected_rows" => $affectedRows,
                "delete_id" => $request->getID()
            ]);
            $response->write($json);
        } else {
            $json = json_encode([
                "status" => "fail",
                "message" => "Record not found."
            ]);
            $response->write($json);
        }

        $response->addHeader("X-Delete-Count", $affectedRows);
    }
}
