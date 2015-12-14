<?php

namespace RSSNext\Connection;

class Connection
{

    protected static $con = false;

    protected function __construct()
    {
    }

    public static function openConnection($this_db = false)
    {

        if ($this_db === false) {
            $this_db = DB_NAME;
        }

        $con = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD) or die('<p>Unable to Connect!</p>');

        //select database:
        $con->select_db($this_db) or die('error selecting: ' . $con->error);
        return $con;
    }

    /**
     * @return \mysqli
     */
    public static function getConnection()
    {

        if (!static::$con) {
            static::$con = static::openConnection();
        }

        return static::$con;
    }
}
