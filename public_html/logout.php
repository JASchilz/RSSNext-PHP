<?php
session_start();

$_SESSION['uid']=False;

session_destroy();
header('Location: /index.php?msg=logged_out');
