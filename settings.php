<?php

ini_set('default_socket_timeout', 10);

// get DB_PASSWORD, FACEBOOK_SECRET from secret_settings
require_once dirname(__FILE__) . '/secret_settings.php';

define('DB_SERVER', 'localhost');
define('DB_NAME', 'rssnext');

define('FACEBOOK_APP_ID', '1578875419016175');

define('ALERT_MESSAGE_CLASS', 0);
define('ALERT_MESSAGE_CONTENT', 1);
const ALERT_MESSAGES = [
    "failed_login" => ["alert-danger", "Your username or password was incorrect."],
    "duplicate_username" => ["alert-danger", "There is already a user with that email."],
    "logged_out" => ["alert-success", "You have successfully logged out."],
];