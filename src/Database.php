<?php

namespace RESTapi\Sources;


class Database
{
    private static $instance = null;
    private $conn = null;
    private $dbExists = false;
    private $db = "";
    private $host = "";
    private $user = "";
    private $pass = "";
    private $charset = "";


    protected function __construct()
    {
        $dbConf = SETTINGS["database"][SETTINGS["db_type"]];
        $this->db = $dbConf["db_name"];
        $this->host = $dbConf["host"];
        $this->user = $dbConf["username"];
        $this->pass = $dbConf["password"];
        $this->charset = $dbConf["charset"];

        $this->conn = @mysqli_connect($this->host, $this->user, $this->pass);
        $this->dbExists = @mysqli_select_db($this->conn, $this->db);
        mysqli_set_charset($this->conn, $this->charset);
    }


    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new Database();
        }

        return self::$instance;
    }


    public function dbCheck()
    {
        if (! $this->conn || ! $this->dbExists) {
            return false;
        } else {
            return true;
        }
    }


    public function dbInfo()
    {
        if (! $this->conn) {
            $info = [
                "status" => "error",
                "message" => "Connection to database failed! Supply the mandatory settings first."
            ];
        } else if (! $this->dbExists) {
            $info = [
                "status" => "error",
                "message" => "Database '$this->db' does not exist."
            ];
        } else {
            $info = [
                "status" => "success"
            ];
        }
        return $info;
    }


    public function select($conf)
    {
        $sql = "SELECT";
        if (isset($conf['columns'])) {
            $sql .= " {$conf['columns']}";
        }

        if (isset($conf['from'])) {
            $sql .= " FROM {$conf['from']}";
        }

        if (isset($conf['where'])) {
            $sql .= " WHERE {$conf['where']}";
        }

        if (isset($conf['groupBy'])) {
            $sql .= " GROUP BY {$conf['groupBy']}";
        }

        if (isset($conf['orderBy'])) {
            $sql .= " ORDER BY {$conf['orderBy']}";
        }

        if (isset($conf['limit'])) {
            $sql .= " LIMIT {$conf['limit']}";
        }

        $rs = mysqli_query($this->conn, $sql);
        return new Recordset($rs);
    }


    public function insert($conf)
    {
        $sql = "INSERT INTO";
        if (isset($conf['into'])) {
            $sql .= " {$conf['into']}";
        }

        if (isset($conf['columns'])) {
            $sql .= " ({$conf['columns']})";
        }

        if (isset($conf['values'])) {
            $valArr = explode(",", $conf['values']);
            $newArr = array();
            foreach ($valArr as $val) {
                $newArr[] = "'" . trim(mysqli_real_escape_string($this->conn, $val)) . "'";
            }
            $newValues = implode(",", $newArr);
            $sql .= " VALUES ($newValues)";
        }
        $res = mysqli_query($this->conn, $sql);
        if ($res) {
            return mysqli_insert_id($this->conn);
        }

        return 0;
    }


    public function update($conf)
    {
        $sql = "UPDATE";
        if (isset($conf['table'])) {
            $sql .= " {$conf['table']}";
        }

        if (isset($conf['set'])) {
            $sql .= " SET {$conf['set']}";
        }

        if (isset($conf['where'])) {
            $sql .= " WHERE {$conf['where']}";
        }

        mysqli_query($this->conn, $sql);

        return mysqli_affected_rows($this->conn);
    }


    public function delete($conf)
    {
        $sql = "DELETE FROM";
        if (isset($conf['from'])) {
            $sql .= " {$conf['from']}";
        }

        if (isset($conf['where'])) {
            $sql .= " WHERE {$conf['where']}";
        }

        mysqli_query($this->conn, $sql);

        return mysqli_affected_rows($this->conn);
    }


    public function __destruct()
    {
        if ($this->conn && mysqli_close($this->conn)) {
            $this->conn = null;
        }
    }

}

class Recordset
{
    private $recordset = null;


    public function __construct($rs)
    {
        $this->recordset = $rs;
    }


    public function getRecordCount()
    {
        return $this->recordset ? mysqli_num_rows($this->recordset) : 0;
    }


    public function reset()
    {
        return $this->recordset ? mysqli_data_seek($this->recordset, 0) : null;
    }


    public function next()
    {
        $record = $this->recordset ? mysqli_fetch_object($this->recordset) : null;
        if ($record) {
            return $record;
        } else {
            return null;
        }
    }


    public function __destruct()
    {
        if ($this->recordset)
            mysqli_free_result($this->recordset);
    }

}



class Record
{
    private $record = null;


    public function __construct($record)
    {
        $this->record = $record;
    }


    public function getProperty($item)
    {
        return $this->record->$item ?? null;
    }


    public function setProperty($item, $value)
    {
        $this->record->$item = $value;
    }


    public function __destruct()
    {
        unset($this->record);
    }
}
