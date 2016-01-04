<?php

namespace RSSNext\Mock;

use RSSNext\Util\Util;

class MockUtil extends Util
{
    public static $sessionStarted = false;

    public static $headers = [];

    protected static function startSession()
    {
        static::$sessionStarted = true;
    }

    protected static function sendHeader($header)
    {
        static::$headers[] = $header;
    }
}