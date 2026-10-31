<?php

require_once __DIR__ . '/vendor/autoload.php';

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
    exit(t('cli.only'));
}

$startTime = microtime(true);
$date = date('Y-m-d H:i:s');

echo "[{$date}] " . t('cleanup.start') . PHP_EOL;

try {
    DB::query("DELETE FROM botkeys WHERE expires < NOW()");
    $deleted = DB::affectedRows();

    $elapsed = round(microtime(true) - $startTime, 4);
    echo "[{$date}] " . t('cleanup.done', ['deleted' => $deleted, 'elapsed' => $elapsed]) . PHP_EOL;
} catch (MeekroDBException $e) {
    echo "[{$date}] " . t('cleanup.error', ['message' => $e->getMessage()]) . PHP_EOL;
    exit(1);
}

exit(0);
