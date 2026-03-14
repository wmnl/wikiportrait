<?php

require_once '../../vendor/autoload.php';

/**
 * Cleanup script voor verlopen bot-tokens.
 * Bedoeld om via een cronjob uitgevoerd te worden.
 *
 * Aanbevolen cronjob (elk uur):
 * 0 * * * * php /pad/naar/cleanup_botkeys.php >> /pad/naar/logs/cleanup.log 2>&1
 */

// Voorkom directe browseraanroep
if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit("Dit script kan alleen via de command line uitgevoerd worden.");
}

$startTime = microtime(true);
$date = date('Y-m-d H:i:s');

echo "[{$date}] Start cleanup verlopen bot-tokens..." . PHP_EOL;

try {
    DB::query("DELETE FROM botkeys WHERE expires < NOW()");
    $deleted = DB::affectedRows();

    $elapsed = round(microtime(true) - $startTime, 4);
    echo "[{$date}] Klaar: {$deleted} verlopen token(s) verwijderd in {$elapsed}s." . PHP_EOL;
} catch (MeekroDBException $e) {
    echo "[{$date}] FOUT: " . $e->getMessage() . PHP_EOL;
    exit(1);
}

exit(0);
