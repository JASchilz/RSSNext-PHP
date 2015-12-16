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
     * @param string $thisDb
     * @return \mysqli
     */
    public static function openConnection($thisDb = "")
    {

        if ($thisDb === "") {
            $thisDb = DB_NAME;
        }

        $con = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD) or die('<p>Unable to Connect!</p>');

        //select database:
        $con->select_db($thisDb) or die('error selecting: ' . $con->error);
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
