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
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    $contentLength = (int) ($_SERVER['CONTENT_LENGTH'] ?? 0);
    $postMaxBytes  = convertToBytes(ini_get('post_max_size'));

    if ($contentLength > $postMaxBytes) {
        ob_clean();
        $protocol = ($_SERVER["HTTPS"] ?? '') === "on" ? "https://" : "http://";
        $host     = $_SERVER['HTTP_HOST'];
        $path     = strtok($_SERVER['REQUEST_URI'], '?');
        header("Location: {$protocol}{$host}{$path}?upload_error=te_groot");
        http_response_code(302);
        exit;
    }
}

$protocol = ($_SERVER["HTTPS"] ?? '') === "on" ? "https://" : "http://";

// Strip port (also handles bracketed IPv6 like [::1]:8080)
$hostname = preg_replace('/:\d+$/', '', $_SERVER['HTTP_HOST'] ?? '');
$hostname = trim($hostname, '[]');
$isLocal  = $hostname === 'localhost' || filter_var($hostname, FILTER_VALIDATE_IP) !== false;

// Cron scripts (CLI) have no host and must never be redirected
if (PHP_SAPI !== 'cli' && substr($hostname, 0, 4) !== 'www.' && !$isLocal) {
    header("Location: {$protocol}www.{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}");
    exit;
}
// phpcs:disable PSR1.Files.SideEffects
// Loads config and necessary stuff
require_once 'config.php';
$basispad = BASE_URL;
require_once ABSPATH . '/vendor/autoload.php';
require_once 'i18n.php';
if (!defined('IMAGE_FOLDER')) {
    define('IMAGE_FOLDER', ABSPATH . "/uploads/images");
    define('THUMB_FOLDER', ABSPATH . "/uploads/thumbs");
    define('GVISION_MACHINE_LEARNING', true);
    define('GV_REQUESTS_LIMIT', 900);
    define('CLOSED', false); //Allow uploads (true = geen uploads)
}
if (!defined('MIN_IMAGE_SIZE')) {
    define('MIN_IMAGE_SIZE', 600); // Minimale lengte van de kortste zijde in pixels
}
header("Access-Control-Allow-Origin: *");
DB::$user = DB_USER;
DB::$password = DB_PASS;
DB::$dbName = DB_DB;
DB::$host = DB_HOST;
require_once 'formfunctions.php';
require_once 'mailfunctions.php';
require_once 'uploadfunctions.php';
require_once 'dashboardfunctions.php';
require_once 'session.php';
// Querylog (optioneel): bevat persoonsgegevens, dus alleen aanzetten om te debuggen en buiten de webroot zetten
if (defined('DB_LOGFILE')) {
    DB::$logfile = DB_LOGFILE;
}
$session = new Session();

// Every POST from a browser must carry the session's CSRF token; the bot API authenticates with headers instead
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && !defined('CSRF_EXEMPT')) {
    $session->checkCsrf();
}

if (!function_exists('is_countable')) {

    function is_countable($var)
    {
        return (is_array($var) || $var instanceof Countable);
    }
}