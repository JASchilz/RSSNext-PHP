<?php

// setup the autoloading
require_once dirname(__FILE__) . '/vendor/autoload.php';

// import the settings
require_once dirname(__FILE__) . '/settings.php';

if (defined('SHOW_ERRORS') && SHOW_ERRORS) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}
