<?php
session_start();

$_SESSION['userId']=false;

session_destroy();
header('Location: /index.php?msg=logged_out');
