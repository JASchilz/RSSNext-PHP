<?php

namespace RSSNext\Connection;

/**
 * Class Connection is a container class for our database connection.
 *
 * @package RSSNext\Connection
 */
class Connection
{

    /** @var \mysqli */
    protected static $con = null;

    /**
     * Disallow class instantiation.
     */
    protected function __construct()
    {
    }

    /**
     * @param string $this_db
     * @return \mysqli
     */
    public static function openConnection($this_db = "")
    {

        if ($this_db === "") {
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
