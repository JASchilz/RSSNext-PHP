<?php
//Handles all login, create account, change password requests via POST.

require_once dirname(__FILE__) . '/../user/user.php';

session_start();


if($_SERVER['REQUEST_METHOD'] == 'POST') {

    $action = $_POST['action'];

	$password_dirty = $_POST['password'];
    $username_dirty = $_POST['username'];
    
    switch ($action) {

        case "login":

            try {
                $_SESSION['uid'] = User::validate($username_dirty, $password_dirty)->uid;
                header('Location: /control_panel.php');
            } catch (UsernameOrPasswordInvalidException $e) {
                header('Location: /index.php?msg=failed_login');
            }

            break;

        case "create_account":
            $password_confirm_dirty = $_POST['password_confirm'];

            if (!filter_var($_POST['username'], FILTER_VALIDATE_EMAIL)) {
                header('Location: /index.php?msg=invalid_email');
                break;
            }
            
            if ($password_dirty != $password_confirm_dirty) {
                header('Location: /index.php?msg=passwords_dont_match');
                break;
            }

            try {
                $user = User::create($username_dirty, $password_dirty);
            } catch (DuplicateUsernameException $e) {
                header("Location: /index.php?msg=duplicate_username");
                break;
            }

            $_SESSION['uid']=$user->uid;
            header('Location: /control_panel.php');

            break;
        default:
            echo "Internal error: unrecognized account_action action.";
            break;
    }

}
else {
	echo "Internal error: unexpected http method.";
}