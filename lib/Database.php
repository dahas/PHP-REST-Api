<?php

namespace RESTapi\Library;

class Database {

    private static $instance;
    private mixed $conn;
    private bool $dbExists;
    private string $db;
    private string $host;
    private string $user;
    private string $pass;
    private string $charset;


    private function __construct()
    {
        $dbConf = SETTINGS["database"][SETTINGS["db_type"]];

        $this->db = $dbConf["db_name"];
        $this->host = $dbConf["host"];
        $this->user = $dbConf["username"];
        $this->pass = $dbConf["password"];
        $this->charset = $dbConf["charset"];

        mysqli_report(MYSQLI_REPORT_OFF);

        try {
            $this->conn = @mysqli_connect($this->host, $this->user, $this->pass);
        } catch (\mysqli_sql_exception $e) {
            echo $e;
        }

        try {
            if ($this->conn) {
                $this->dbExists = @mysqli_select_db($this->conn, $this->db);
                mysqli_set_charset($this->conn, $this->charset);
            }
        } catch (\mysqli_sql_exception $e) {
            echo $e;
        }
    }


    public static function getInstance(): Database|null
    {
        if (self::$instance == null) {
            self::$instance = new Database();
        }

        return self::$instance;
    }


    public function dbCheck(): bool
    {
        if (!$this->conn || !$this->dbExists) {
            return false;
        } else {
            return true;
        }
    }


    public function dbInfo(): array
    {
        if (!$this->conn) {
            $info = [
                "status" => "error",
                "message" => "Connection to database failed!"
            ];
        } else if (!$this->dbExists) {
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


    public function select(array $conf): Recordset
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


    public function insert(array $conf): int
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


    public function update(array $conf): int
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


    public function delete(array $conf): int
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

class Recordset {

    private $recordset = null;


    public function __construct($rs)
    {
        $this->recordset = $rs;
    }


    public function getRecordCount(): int
    {
        return $this->recordset instanceof \mysqli_result ? 
            mysqli_num_rows($this->recordset) : 0;
    }


    public function reset(): void
    {
        if($this->recordset instanceof \mysqli_result) {
            mysqli_data_seek($this->recordset, 0);
        }
    }


    public function next(): object|null
    {
        $record = $this->recordset ? mysqli_fetch_object($this->recordset) : null;
        if ($record) {
            return new Record($record);
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



class Record {
    
    private $record = null;


    public function __construct($record)
    {
        $this->record = $record;
    }


    public function getAll(): array
    {
        return (array) $this->record ?? [];
    }


    public function getProperty(string $item): mixed
    {
        return $this->record->$item ?? null;
    }


    public function setProperty(string $item, mixed $value): void
    {
        $this->record->$item = $value;
    }


    public function __destruct()
    {
        unset($this->record);
    }
}