<?php

namespace RSSNext\Util;

/**
 * Class Util is a static helper class providing a few utility methods.
 *
 * @package RSSNext\Util
 */
class Util
{
    /**
     * Disallow class instantiation
     */
    protected function __construct()
    {
    }

    /**
     * Format a php array into a single-string list suitable for a MySQL query.
     *
     * @param array $array
     * @return string
     */
    public static function makeList(array $array)
    {

        $list = "(";
        foreach ($array as $key) {
            $list .= $key . ", ";
        }
        $list = rtrim($list, ', ');
        $list .= ")";

        return $list;
    }

    /**
     * Get the user's id from the session.
     *
     * @return null|string
     */
    public static function getUid()
    {
        return array_key_exists('uid', $_SESSION) ? $_SESSION['uid'] : null;
    }

    /**
     * Initialize the session.
     *
     * @return null|string
     */
    public static function initSession()
    {
        $lifetime=60 * 60 * 24 * 60;
        session_set_cookie_params($lifetime);
        session_start();

        return static::getUid();
    }

    /**
     * If the visitor has an active login, find their uid. If not, bounce them to login.
     *
     * @return boolean|null|string
     */
    public static function initOrBump()
    {

        $uid = static::initSession();

        if (!$uid) {
            header("Location: /index.php?msg=expired_login");
            return false;
        } else {
            return $uid;
        }

    }
}
