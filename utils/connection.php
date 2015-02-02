<?php

require_once dirname(__FILE__) . '/../settings.php';


function openConnection($this_db = False)
{
    global $db_server, $db_username, $db_password, $db;

    if ($this_db === False) {
        $this_db = $db;
    }

    $con = mysqli_connect($db_server,$db_username,$db_password) or die ('<p>Unable to Connect!</p>');

    //select database:
    $con->select_db($this_db) or die ('error selecting: ' . $con->error);
    return $con;
}

$con = False;

function getConnection() {
    global $con;

    if (!$con) {
        $con = openConnection();
    }

    return $con;
}