<?php

namespace RESTapi\Library\v1;

use RESTapi\Sources\WebService;
use RESTapi\Sources\Database;
use RESTapi\Sources\Response;


class Users extends WebService {

    private Response $response;
    private array $fields;


    public function __construct()
    {
        $this->response = new Response();
        $this->fields = ["name", "age", "city", "country"];
    }

    // GET
    public function read(array $params = [])
    {
        $db = Database::getInstance();
        if (!$db->dbCheck()) {
            $json = json_encode($db->dbInfo());
            return false;
        }

        $query = [];
        $query["columns"] = "uid, name, age, city, country";
        $query["from"] = "sampledata";

        if (isset($params["uid"]) && $params["uid"])
            $query["where"] = "uid=" . $params["uid"];

        $recordset = $db->select($query);

        if (!$recordset) {
            $json = json_encode([
                "status" => "error",
                "message" => "Query could not be executed."
            ]);
            $this->response->write($json);
            return false;
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
            $this->response->write($json);
        } else if ($affectedRows > 1) {
            $json = json_encode([
                "status" => "success",
                "data" => [
                    "users" => $users
                ]
            ]);
            $this->response->write($json);
        } else {
            $json = json_encode([
                "status" => "fail",
                "message" => "No record found."
            ]);
            $this->response->write($json);
        }

        $this->response->addHeader("X-Data-Count", $affectedRows);
        $this->response->flush();

        return true;
    }

    // POST
    public function create($params)
    {
        $db = Database::getInstance();
        if (!$db->dbCheck()) {
            $json = json_encode($db->dbInfo());
            $this->response->write($json);
            return false;
        }

        $data = [
            $params["name"],
            $params["age"],
            $params["city"],
            $params["country"]
        ];

        // Quote values:
        array_walk($data, function (&$item) {
            $item = "$item";
        });

        $query = [];
        $query["into"] = "sampledata";
        $query["columns"] = "name, age, city, country";
        $query["values"] = implode(", ", $data);

        $uid = $db->insert($query);

        if (!$uid) {
            $json = json_encode([
                "status" => "error",
                "message" => "Query could not be executed."
            ]);
            $this->response->write($json);
            return false;
        }

        $json = json_encode([
            "status" => "success",
            "insert_id" => $uid,
            "data" => $data
        ]);
        $this->response->write($json);

        $this->response->addHeader("X-Insert-Count", 1);
        $this->response->flush();

        return true;
    }

    // PUT
    public function update(array $params)
    {
        $db = Database::getInstance();
        if (!$db->dbCheck()) {
            $json = json_encode($db->dbInfo());
            $this->response->write($json);
            return false;
        }

        if (!isset($params["uid"]) || !$params["uid"]) {
            $json = json_encode([
                "status" => "error",
                "message" => "Please provide a unique ID."
            ]);
            $this->response->write($json);
            $this->response->flush();
            return false;
        }

        $data = [];

        foreach ($this->fields as $field) {
            if ($params[$field]) {
                $data[$field] = $params[$field];
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
        $query["where"] = "uid=" . $params["uid"];

        $affectedRows = $db->update($query);

        if ($affectedRows > 0) {
            $json = json_encode([
                "status" => "success",
                "affected_rows" => $affectedRows,
                "update_id" => $params["uid"],
                "data" => $data
            ]);
            $this->response->write($json);
        } else if ($affectedRows < 0) {
            $json = json_encode([
                "status" => "fail",
                "message" => "Query could not be executed."
            ]);
            $this->response->write($json);
        } else {
            $json = json_encode([
                "status" => "fail",
                "message" => "Either no changes detected or the target record does not exist."
            ]);
            $this->response->write($json);
        }

        $this->response->addHeader("X-Update-Count", $affectedRows);
        $this->response->flush();

        return true;
    }

    // DELETE
    public function delete(array $params)
    {
        $db = Database::getInstance();
        if (!$db->dbCheck()) {
            $json = json_encode($db->dbInfo());
            $this->response->write($json);
            return false;
        }

        if (!isset($params["uid"]) || !$params["uid"]) {
            $json = json_encode([
                "status" => "error",
                "message" => "Please provide a unique ID."
            ]);
            $this->response->write($json);
            $this->response->flush();
            return false;
        }

        $query = [];
        $query["from"] = "sampledata";
        $query["where"] = "uid=" . $params["uid"];

        $affectedRows = $db->delete($query);

        if ($affectedRows) {
            $json = json_encode([
                "status" => "success",
                "affected_rows" => $affectedRows,
                "delete_id" => $params["uid"]
            ]);
            $this->response->write($json);
        } else {
            $json = json_encode([
                "status" => "fail",
                "message" => "Record not found."
            ]);
            $this->response->write($json);
        }

        $this->response->addHeader("X-Delete-Count", $affectedRows);
        $this->response->flush();

        return true;
    }
}