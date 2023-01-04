<?php


class WebAPI
{
    public function __construct()
    {
        if (php_sapi_name() == "cli") {
            global $__site_config;
            $__site_config_path ='/home/17ahmed2002/appconfig.json';
            $__site_config = file_get_contents($__site_config_path);
        } elseif (php_sapi_name() == "apache2handler") {
            global $__site_config;
            $__site_config_path = dirname(is_link($_SERVER['DOCUMENT_ROOT']) ? readlink($_SERVER['DOCUMENT_ROOT']) : $_SERVER['DOCUMENT_ROOT']).'/appconfig.json';
            // print(dirname($_SERVER['DOCUMENT_ROOT']));
            // print("<br>".$_SERVER['DOCUMENT_ROOT']."<br>");
            // print(readlink($_SERVER['DOCUMENT_ROOT']));
            $__site_config = file_get_contents($__site_config_path);
        }
        Database::getConnection();
    }

    public function initiateSession()
    {
        Session::start();
    }
}
