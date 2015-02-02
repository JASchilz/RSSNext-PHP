<?php
// Handle a successful Facebook javascript login


require_once dirname(__FILE__).'/../user/user.php';
require_once dirname(__FILE__).'/../settings.php';

require_once dirname(__FILE__).'/../vendor/autoload.php';

use Facebook\FacebookSession;
use Facebook\FacebookRequestException;
use Facebook\FacebookJavaScriptLoginHelper;


FacebookSession::setDefaultApplication($facebook_app_id, $facebook_secret);

session_start();

$helper = new FacebookJavaScriptLoginHelper();
try {
    $session = $helper->getSession();
} catch (FacebookRequestException $ex) {
    // When Facebook returns an error
    header('Location: /index.php?msg=failed_social_login');
} catch (\Exception $ex) {
    header('Location: /index.php?msg=failed_social_login');
}

if ($session) {
    $_SESSION['uid'] = User::fromFacebookId($session->getUserId())->uid;
    header('Location: /control_panel.php');
}




