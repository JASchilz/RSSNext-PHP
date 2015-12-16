<?php
//Handles all login, create account, change password requests via POST.

require_once dirname(__FILE__) . '/../setup.php';

use RSSNext\User\User;
use RSSNext\Exception\UsernameOrPasswordInvalidException;
use RSSNext\Exception\DuplicateUsernameException;

session_start();


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $action = $_POST['action'];

    $passwordDirty = $_POST['password'];
    $usernameDirty = $_POST['username'];
    
    switch ($action) {

        case "login":
            try {
                $_SESSION['userId'] = User::validate($usernameDirty, $passwordDirty)->getUserId();
                header('Location: /control_panel.php');
            } catch (UsernameOrPasswordInvalidException $e) {
                header('Location: /index.php?msg=failed_login');
            }

            break;

        case "create_account":
            $passwordConfirmDirty = $_POST['password_confirm'];

            if (!filter_var($_POST['username'], FILTER_VALIDATE_EMAIL)) {
                header('Location: /index.php?msg=invalid_email');
                break;
            }
            
            if ($passwordDirty != $passwordConfirmDirty) {
                header('Location: /index.php?msg=passwords_dont_match');
                break;
            }

            try {
                $user = User::create($usernameDirty, $passwordDirty);
            } catch (DuplicateUsernameException $e) {
                header("Location: /index.php?msg=duplicate_username");
                break;
            }

            $_SESSION['userId']=$user->getUserId();
            header('Location: /control_panel.php');

            break;
        default:
            echo "Internal error: unrecognized account_action action.";
            break;
    }

} else {
    echo "Internal error: unexpected http method.";
}
