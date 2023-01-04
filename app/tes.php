<?php

//include 'libs/load.php';
// $conn = Database::getConnection();
// printf("\n");
// $conn = Database::getConnection();
// $conn = Database::getConnection();
// $conn = Database::getConnection();
// $conn = Database::getConnection();

function get_config($key, $default=null)
{
    global $__site_config;
    $array = json_decode($__site_config, true);
    if (isset($array[$key])) {
        return $array[$key];
    } else {
        return $default;
    }
}
print(get_config('db_name', 'dfb'));
