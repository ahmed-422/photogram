<?php

include_once 'includes/Database.class.php';
include_once 'includes/User.class.php';
include_once 'includes/Session.class.php';
include_once 'includes/UserSession.class.php';
include_once 'includes/WebAPI.class.php';
$wapi = new WebAPI();
$wapi->initiateSession();


function get_config($key, $default=null)
{
    //learn global keyword
    global $__site_config;
    $array = json_decode($__site_config, true);
    if (isset($array[$key])) {
        return $array[$key];
    } else {
        return $default;
    }
}

function load_template($name)
{
    include $_SERVER['DOCUMENT_ROOT']."/app/_templates/$name.php";
}

function validate_credentias($usr, $pass)
{
    if ($usr == 'ahmed@gmail.com' and $pass == 'password') {
        return true;
    } else {
        return false;
    }
}
