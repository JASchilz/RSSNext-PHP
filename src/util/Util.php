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
    public static function getUserId()
    {
        return array_key_exists('userId', $_SESSION) ? $_SESSION['userId'] : null;
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

        return static::getUserId();
    }

    /**
     * If the visitor has an active login, find their uid. If not, bounce them to login.
     *
     * @return boolean|null|string
     */
    public static function initOrBump()
    {

        $userId = static::initSession();

        if (!$userId) {
            header("Location: /index.php?msg=expired_login");
            return false;
        } else {
            return $userId;
        }

    }

    /**
     * Returns the current [alert message class, alert message content].
     *
     * @return string[]
     */
    public static function getAlertMessage()
    {
        $alertMessage = [
                         "hidden",
                         "",
                        ];

        if (array_key_exists("msg", $_GET) && array_key_exists($_GET["msg"], ALERT_MESSAGES)) {
            $alertMessages = ALERT_MESSAGES;
            $alertMessage = $alertMessages[$_GET["msg"]];
        }

        return $alertMessage;
    }
}
