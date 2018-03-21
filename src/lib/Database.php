<?php

namespace php_rest\src\lib;

/**
 * Class Database
 * @package php_rest\src\lib
 */
class Database
{
    private static $instance = null;
    private $conn = null;
    private $dbExists = false;
    private $db = "";
    private $host = "";
    private $user = "";
    private $pass = "";
    private $charset = "utf8";

    /**
     * Protected constructor since we use a singleton.
     */
    protected function __construct()
    {
        $dbConf = $GLOBALS["database"][$GLOBALS["db_type"]];
        $this->db = $dbConf["db_name"];
        $this->host = $dbConf["host"];
        $this->user = $dbConf["username"];
        $this->pass = $dbConf["password"];
        $this->charset = $dbConf["charset"];

        $this->conn = @mysqli_connect($this->host, $this->user, $this->pass);
        $this->dbExists = @mysqli_select_db($this->conn, $this->db);
        mysqli_set_charset($this->conn, $this->charset);
    }

    /**
     * Singleton
     * @return null|DB
     */
    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new Database();
        }

        return self::$instance;
    }

    /**
     * Check if database is available
     */
    public function dbCheck()
    {
        if (! $this->conn || ! $this->dbExists) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Return info about a connection error
     */
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

    /**
     * @param array $conf
     * @return Recordset
     */
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

    /**
     * @param array $conf
     * @return int
     */
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

    /**
     * @param array $conf
     * @return bool|mysqli_result
     */
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

    /**
     * @param array $conf
     * @return bool|mysqli_result
     */
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

    /**
     * Destructor
     */
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

    /**
     * Recordset constructor.
     * @param $rs
     */
    public function __construct($rs)
    {
        $this->recordset = $rs;
    }

    /**
     * @return int
     */
    public function getRecordCount()
    {
        return $this->recordset ? mysqli_num_rows($this->recordset) : 0;
    }

    /**
     * @return bool
     */
    public function reset()
    {
        return $this->recordset ? mysqli_data_seek($this->recordset, 0) : null;
    }

    /**
     * Iterate through collection
     * 
     * @return array|null|object
     */
    public function next()
    {
        $record = $this->recordset ? mysqli_fetch_object($this->recordset) : null;
        if ($record) {
            return $record;
        } else {
            return null;
        }
    }

    /**
     * Destructor
     */
    public function __destruct()
    {
        if ($this->recordset)
            mysqli_free_result($this->recordset);
    }

}


/**
 * Class Record
 * @package makeup\lib
 */
class Record
{
    private $record = null;

    /**
     * Record constructor.
     * 
     * @param object $record Single record
     */
    public function __construct($record)
    {
        $this->record = $record;
    }

    /**
     * Access a property.
     * 
     * @param string $item
     * @return mixed $value
     */
    public function getProperty($item)
    {
        return $this->record->$item ?? null;
    }

    /**
     * Change the value of a property.
     * 
     * @param string $item
     * @param mixed $value
     */
    public function setProperty($item, $value)
    {
        $this->record->$item = $value;
    }

    /**
     * Destructor
     */
    public function __destruct()
    {
        unset($this->record);
    }
}
