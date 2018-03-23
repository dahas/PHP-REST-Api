<?php

namespace php_rest\src\views\Example\v1;

use php_rest\src\lib\ViewBase;
use php_rest\src\lib\Database;


class ExampleView extends ViewBase
{
    // From ViewBase:
    protected $allowedRequestMethods = ["GET", "POST", "PUT", "DELETE"];
    protected $requiresAuthentication = true;

    // Custom:
    private $fields = ["name", "age", "city", "country"];

    /**
     * This function is called when $requiresAuthentication is true.
     * Get username and password from a database.
     * Make sure the password is stored SHA1 encrypted.
     */
    public function authenticate()
    {
        $db = Database::getInstance();

        // ToDo: Get from DB
        $username = 'localtest';
        $password = sha1('secret');

        $this->setUsername($username);
        $this->setPassword($password);
    }
    
    // GET
    public function read($request=null, $response=null)
    {
        $db = Database::getInstance();
        if (! $db->dbCheck()) {
            echo json_encode($db->dbInfo());
            return false;
        }

        $query = [];
        $query["columns"] = "uid, name, age, city, country";
        $query["from"] = "sampledata";

        if ($request->getParameter("uid"))
            $query["where"] = "uid=".$request->getParameter("uid");

        $recordset = $db->select($query);

        if (! $recordset) {
            echo json_encode([
                "status" => "error",
                "message" => "Query could not be executed."
            ]);
            return false;
        }

        $affectedRows = $recordset->getRecordCount();

        $posts = [];
        while ($record = $recordset->next()) {
            $posts[] = $record;
        }

        if ($affectedRows == 1) {
            echo json_encode([
                "status" => "success",
                "data" => [
                    "post" => $posts[0]
                ]
            ]);
        } else if ($affectedRows > 1) {
            echo json_encode([
                "status" => "success",
                "data" => [
                    "posts" => $posts
                ]
            ]);
        } else {
            echo json_encode([
                "status" => "fail",
                "message" => "No record found."
            ]);
        }

        $response->addHeader("X-Data-Count", $affectedRows);

        return true;
    }

    // POST
    public function create($request=null, $response=null)
    {
        $db = Database::getInstance();
        if (! $db->dbCheck()) {
            echo json_encode($db->dbInfo());
            return false;
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

        $uid = $db->insert($query);

        if (! $uid) {
            echo json_encode([
                "status" => "error",
                "message" => "Query could not be executed."
            ]);
            return false;
        }

        echo json_encode([
            "status" => "success",
            "insert_id" => $uid,
            "data" => $data
        ]);

        $response->addHeader("X-Insert-Count", 1);

        return true;
    }

    // PUT
    public function update($request=null, $response=null)
    {
        $db = Database::getInstance();
        if (! $db->dbCheck()) {
            echo json_encode($db->dbInfo());
            return false;
        }

        if (! $request->getParameter("uid")) {
            echo json_encode([
                "status" => "error",
                "message" => "Please provide a unique ID."
            ]);
            return false;
        }

        $data = [];

        foreach ($this->fields as $field) {
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
        $query["where"] = "uid=".$request->getParameter("uid");

        $affectedRows = $db->update($query);

        if ($affectedRows > 0) {
            echo json_encode([
                "status" => "success",
                "affected_rows" => $affectedRows,
                "update_id" => $request->getParameter("uid"),
                "data" => $data
            ]);
        } else if ($affectedRows < 0) {
            echo json_encode([
                "status" => "fail",
                "message" => "Query could not be executed."
            ]);
        } else {
            echo json_encode([
                "status" => "fail",
                "message" => "Either no changes detected or the target record does not exist."
            ]);
        }

        $response->addHeader("X-Update-Count", $affectedRows);

        return true;
    }

    // DELETE
    public function delete($request=null, $response=null)
    {
        $db = Database::getInstance();
        if (! $db->dbCheck()) {
            echo json_encode($db->dbInfo());
            return false;
        }

        if (! $request->getParameter("uid")) {
            echo json_encode([
                "status" => "error",
                "message" => "Please provide a unique ID."
            ]);
            return false;
        }

        $query = [];
        $query["from"] = "sampledata";
        $query["where"] = "uid=".$request->getParameter("uid");

        $affectedRows = $db->delete($query);

        if ($affectedRows) {
            echo json_encode([
                "status" => "success",
                "affected_rows" => $affectedRows,
                "delete_id" => $request->getParameter("uid")
            ]);
        } else {
            echo json_encode([
                "status" => "fail",
                "message" => "Record not found."
            ]);
        }

        $response->addHeader("X-Delete-Count", $affectedRows);

        return true;
    }
}
