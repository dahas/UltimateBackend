<?php

namespace UltimateBackend\lib;


/**
 * Class DB
 * @package UltimateBackend\lib
 */
class DB
{
    private $conn = null;

    /**
     * @param string $db
     * @param string $host
     * @param string $user
     * @param string $pass
     * @param string $charset
     */
    public function __construct($db, $host = "localhost", $user = "root", $pass = "", $charset = "utf8")
    {
        $this->conn = mysqli_connect($host, $user, $pass)
        or die("Connection to database failed!");

        mysqli_select_db($this->conn, $db)
        or die("Database doesn´t exist!");

        mysqli_set_charset($this->conn, $charset);
    }

    /**
     * @param array $conf
     * @return Recordset
     */
    public function select($conf)
    {
        $sql = "SELECT";
        if (isset($conf['columns']))
            $sql .= " {$conf['columns']}";
        if (isset($conf['from']))
            $sql .= " FROM {$conf['from']}";
        if (isset($conf['where']))
            $sql .= " WHERE {$conf['where']}";
        if (isset($conf['groupBy']))
            $sql .= " GROUP BY {$conf['groupBy']}";
        if (isset($conf['orderBy']))
            $sql .= " ORDER BY {$conf['orderBy']}";
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
        if (isset($conf['into']))
            $sql .= " {$conf['into']}";
        if (isset($conf['columns']))
            $sql .= " ({$conf['columns']})";
        if (isset($conf['values'])) {
            $valArr = explode(",", $conf['values']);
            $newArr = array();
            foreach ($valArr as $val) {
                $newArr[] = "'" . mysqli_real_escape_string($this->conn, $val) . "'";
            }
            $newValues = implode(",", $newArr);
            $sql .= " VALUES ($newValues)";
        }
        $res = mysqli_query($this->conn, $sql);
        if ($res)
            return mysqli_insert_id($this->conn);
        return 0;
    }

    /**
     * @param array $conf
     * @return bool|\mysqli_result
     */
    public function update($conf)
    {
        $sql = "UPDATE";
        if (isset($conf['table']))
            $sql .= " {$conf['table']}";
        if (isset($conf['set']))
            $sql .= " SET {$conf['set']}";
        if (isset($conf['where']))
            $sql .= " WHERE {$conf['where']}";
        return mysqli_query($this->conn, $sql);
    }

    /**
     * @param array $conf
     * @return bool|\mysqli_result
     */
    public function delete($conf)
    {
        $sql = "DELETE FROM";
        if (isset($conf['from']))
            $sql .= " {$conf['from']}";
        if (isset($conf['where']))
            $sql .= " WHERE {$conf['where']}";
        return mysqli_query($this->conn, $sql);
    }

    public function __destruct()
    {
        mysqli_close($this->conn);
        unset($this);
    }

}


class Recordset
{
    private $recordset = null;

    const FETCH_ROW = 0;
    const FETCH_ASSOC = 1;

    public function __construct($rs)
    {
        $this->recordset = $rs;
    }

    public function getRowCount()
    {
        return mysqli_num_rows($this->recordset);
    }

    public function getRow($fetch = self::FETCH_ROW)
    {
        if ($fetch === self::FETCH_ASSOC)
            return mysqli_fetch_assoc($this->recordset);
        return mysqli_fetch_row($this->recordset);
    }

    public function __destruct()
    {
        mysqli_free_result($this->recordset);
        unset($this);
    }

}