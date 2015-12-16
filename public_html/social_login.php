<?php
// Handle a successful Facebook javascript login

require_once dirname(__FILE__) . '/../setup.php';

use Facebook\FacebookSession;
use Facebook\FacebookRequestException;
use Facebook\FacebookJavaScriptLoginHelper;

FacebookSession::setDefaultApplication(FACEBOOK_APP_ID, FACEBOOK_SECRET);

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
    $_SESSION['userId'] = User::fromFacebookId($session->getUserId())->getUid();
    header('Location: /control_panel.php');
}
