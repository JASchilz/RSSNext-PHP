<?php

namespace RSSNext\Model;

use RSSNext\Connection\Connection;

/**
 * Class Model is an abstract parent class for those classes which are persisted
 * to the database.
 *
 * @package RSSNext\Model
 */
abstract class Model
{

    /**
     * @return \mysqli
     */
    protected static function getConnection()
    {
        return Connection::getConnection();
    }
}
