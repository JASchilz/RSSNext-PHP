<?php


function gedUid() {
    return $_SESSION['uid'];
}

function initSession() {
    $lifetime=60 * 60 * 24 * 60;
    session_set_cookie_params($lifetime);
    session_start();
    
    return gedUid();
}

function initOrBump() {

    $uid = initSession();
    
    if(!$uid) {
        header("Location: /index.php?msg=expired_login");
        return False;
    } else {
        return $uid;
    }

}