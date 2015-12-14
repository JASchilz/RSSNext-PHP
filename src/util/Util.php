<?php

namespace RSSNext\Util;

class Util
{
    protected function __construct()
    {
    }

    public static function makeList($array)
    {
        // Format a php array into a single-string list suitable for a MySQL query.

        $list = "(";
        foreach ($array as $key) {
            $list .= $key . ", ";
        }
        $list = rtrim($list, ', ');
        $list .= ")";

        return $list;
    }

    public static function getUid()
    {
        return array_key_exists('uid', $_SESSION) ? $_SESSION['uid'] : null;
    }

    public static function initSession()
    {
        $lifetime=60 * 60 * 24 * 60;
        session_set_cookie_params($lifetime);
        session_start();

        return static::getUid();
    }

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
