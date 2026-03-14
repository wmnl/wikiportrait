<?php

ob_start();
if (!function_exists('convertToBytes')) {
    function convertToBytes(string $val): int
    {
        $val  = trim($val);
        $last = strtolower($val[strlen($val) - 1]);
        $num  = (int) $val;
        return match ($last) {
            'g' => $num * 1024 * 1024 * 1024,
            'm' => $num * 1024 * 1024,
            'k' => $num * 1024,
            default => $num,
        };
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $contentLength = (int) ($_SERVER['CONTENT_LENGTH'] ?? 0);
    $postMaxBytes  = convertToBytes(ini_get('post_max_size'));

    if ($contentLength > $postMaxBytes) {
        ob_clean();
        $protocol = (@$_SERVER["HTTPS"] == "on") ? "https://" : "http://";
        $host     = $_SERVER['HTTP_HOST'];
        $path     = strtok($_SERVER['REQUEST_URI'], '?');
        header("Location: {$protocol}{$host}{$path}?upload_error=te_groot");
        http_response_code(302);
        exit;
    }
}

$protocol = (@$_SERVER["HTTPS"] == "on") ? "https://" : "http://";

if (substr($_SERVER['HTTP_HOST'], 0, 4) !== 'www.' && $_SERVER['HTTP_HOST'] !== 'localhost') {
    header("Location: {$protocol}www.{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}");
    exit;
}
// phpcs:disable PSR1.Files.SideEffects
// Loads config and necessary stuff
require_once 'config.php';
$basispad = BASE_URL;
require_once ABSPATH . '/vendor/autoload.php';
if (!defined('IMAGE_FOLDER')) {
    define('IMAGE_FOLDER', ABSPATH . "/uploads/images");
    define('THUMB_FOLDER', ABSPATH . "/uploads/thumbs");
    define('ADMIN_ERROR', "<br>Deze pagina is voor admins bedoeld.<br>");
    define('DUPLICATE_ERROR', "Deze afbeedling is al geüpload");
    define('GVISION_MACHINE_LEARNING', true);
    define('GV_REQUESTS_LIMIT', 900);
    define('CLOSED', false); //Allow uploads (true = geen uploads)
}
header("Access-Control-Allow-Origin: *");
DB::$user = DB_USER;
DB::$password = DB_PASS;
DB::$dbName = DB_DB;
DB::$host = DB_HOST;
require_once 'formfunctions.php';
require_once 'uploadfunctions.php';
require_once 'dashboardfunctions.php';
require_once 'session.php';
DB::$logfile = ABSPATH . '/db_logfile.txt';
$session = new Session();

if (!function_exists('is_countable')) {

    function is_countable($var)
    {
        return (is_array($var) || $var instanceof Countable);
    }
}