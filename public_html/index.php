<?php
require_once dirname(__FILE__) . '/../setup.php';

use RSSNext\Util\Util;

// If the user is logged in, send them to the control panel
if (Util::initSession()) {
    header('Location: control_panel.php');
}

$alertMessageClass = Util::getAlertMessage()[ALERT_MESSAGE_CLASS];
$alertMessageContent = Util::getAlertMessage()[ALERT_MESSAGE_CONTENT];

?>
<!DOCTYPE html>

<html>
<head>
    <title>RSSNext - One Click Takes You To Your Next Unread Item</title>
    <link rel="icon" href="includes/media/favicon.ico" type="image/x-icon" />

    <!-- JQuery -->
    <script type="text/javascript" src='//code.jquery.com/jquery-2.1.1.min.js'></script>

    <!-- Bootstrap -->
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css" rel="stylesheet">
    <script type="text/javascript" src='//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js'></script>

    <!-- Font Awesome and Bootstrap Social -->
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-social/4.2.1/bootstrap-social.min.css" rel="stylesheet">

    <!-- Login CSS -->
    <link href='includes/css/login.css' rel="stylesheet">

    <!-- Custom js -->
    <script type="application/x-javascript">
        var rssnext = {};
    </script>
    <script type="application/x-javascript" src="includes/js/social_login.js"></script>

</head>

<body>
<div id="alert-message" class="alert <?php echo $alertMessageClass;?>" role="alert">
        <?php echo $alertMessageContent;?>
</div>

<div class="container">

    <div class="page-header">
        <h1>RSSNext
            <small id="header-small">&nbsp;A single click takes you to your next unread item.</small>
        </h1>
    </div>
    <div class="row">

        <div class="col-xs-12 col-sm-12 col-md-6 pull-right">
            <div class="main well opaque">

                <h3>Sign Up</h3>
                <div class="row">
                    <div class="col-xs-7 col-sm-7 col-md-7 center-block">
                        <a class="btn btn-block btn-social btn-facebook"
                           onClick="FB.login(function(response) {rssnext.social_login.checkLoginState();});">
                            <i class="fa fa-facebook"></i>Sign up with Facebook
                        </a>
                    </div>
                </div>
                <div class="login-or">
                    <span class="span-or">or</span>
                </div>

                <form id="signup-form" class="login" method="POST" action="account_actions.php">
                    <input type="hidden" name="action" value="create_account">

                    <div class="form-group">
                        <label for="id_username">E-mail</label>
                        <input type="username" class="form-control" id="id_username" name="username">
                    </div>

                    <div class="form-group">
                        <label for="id_password">Password</label>
                        <input type="password" class="form-control" id="id_password" name="password">
                    </div>

                    <div class="form-group">
                        <label for="id_password_confirm">Password (again)</label>
                        <input type="password" class="form-control" id="id_password_confirm" name="password_confirm">
                    </div>

                    <button type="submit" class="btn btn btn-primary">
                        Sign Up
                    </button>

                </form>

            </div>
        </div>


        <div class="col-xs-12 col-sm-12 col-md-6">
            <div class="main well">

                <h3>Log In</h3>
                <div class="row">
                    <div class="col-xs-7 col-sm-7 col-md-7 center-block">
                        <a class="btn btn-block btn-social btn-facebook"
                           onClick="FB.login(function(response) {rssnext.social_login.checkLoginState();});">
                            <i class="fa fa-facebook"></i>Log in with Facebook
                        </a>
                    </div>

                </div>
                <div class="login-or">
                    <span class="span-or">or</span>
                </div>

                <form id="login-form" class="login" method="POST" action="account_actions.php">
                    <input type="hidden" name="action" value="login">

                    <div class="form-group">
                        <label for="id_login">E-mail</label>
                        <input type="email" class="form-control" id="id_username" name="username">
                    </div>

                    <div class="form-group">
                        <label for="id_password">Password</label>
                        <input type="password" class="form-control" id="id_password"name="password">
                    </div>

                    <button type="submit" class="btn btn btn-primary">
                        Log In
                    </button>

                </form>

            </div>
        </div>
    </div>
</div>

</body>
</html>