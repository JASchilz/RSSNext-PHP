<?php

ini_set('default_socket_timeout', 10);

// get DB_PASSWORD, FACEBOOK_SECRET from secret_settings
require_once dirname(__FILE__) . '/secret_settings.php';

define('DB_SERVER', 'localhost');
define('DB_NAME', 'rssnext');

define('FACEBOOK_APP_ID', '1578875419016175');
