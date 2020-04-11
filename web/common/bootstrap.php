<?php
    // Loads config and necessary stuff
    require 'config.php';
    $basispad = BASE_URL;
    require ABSPATH . '/vendor/autoload.php';
    define('IMAGE_FOLDER', ABSPATH . "/uploads/images");
    define('THUMB_FOLDER', ABSPATH . "/uploads/thumbs");
    define('ADMIN_ERROR', "<br>Deze pagina is voor admins bedoeld.<br>");
    define('DUPLICATE_ERROR', "Deze afbeedling is al geüpload");
    define('GVISION_MACHINE_LEARNING',true);
    define('GV_REQUESTS_LIMIT', 900);
    header("Access-Control-Allow-Origin: *");
    DB::$user = DB_USER;
    DB::$password = DB_PASS;
    DB::$dbName = DB_DB;
    DB::$host = DB_HOST;
    require 'formfunctions.php';
    require 'uploadfunctions.php';
    require 'dashboardfunctions.php';
    require 'session.php';

    $session = new Session();
