<?php

namespace UltimateBackend\lib;


class DB
{
    private $conn = null;
    private $config = null;

    public function __construct()
    {
        $this->config = Base::getConfig();

        $this->conn = mysqli_connect(
            $this->config['database']['Host'],
            $this->config['database']['Username'],
            $this->config['database']['Password']
        )
        or die("Connection to database failed!");

        mysqli_select_db(
            $this->conn,  $this->config['database']['DB_Name']
        )
        or die("Database doesn´t exist!");
    }

    public static function connect()
    {
        return new DB();
    }

    public function select($query)
    {
        $rs = mysqli_query($this->conn, $query);
        return new Recordset($rs);
    }

    public function insert($query)
    {
        $res = mysqli_query($this->conn, $query);
        if($res)
            return mysqli_insert_id($this->conn);
        return 0;
    }

    public function update($query)
    {
        return mysqli_query($this->conn, $query);
    }

    public function delete($query)
    {
        return mysqli_query($this->conn, $query);
    }

    public function __destruct()
    {
        mysqli_close($this->conn);
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

    public function getRow($fetch=self::FETCH_ROW)
    {
        if($fetch===self::FETCH_ASSOC)
            return mysqli_fetch_assoc($this->recordset);
        return mysqli_fetch_row($this->recordset);
    }

    public function __destruct()
    {
        mysqli_free_result($this->recordset);
    }

}