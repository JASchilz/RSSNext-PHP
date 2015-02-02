<?php


function makeList($array) {
    // Format a php array into a single-string list suitable for a MySQL query.

    $list = "(";
    foreach ($array as $key) {
        $list .= $key . ", ";
    }
    $list = rtrim($list, ', ');
    $list .= ")";

    return $list;
}