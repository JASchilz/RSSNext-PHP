<?php
session_start();

$_SESSION['uid']=false;

session_destroy();
header('Location: /index.php?msg=logged_out');
