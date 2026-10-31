<?php

require_once __DIR__ . '/../common/bootstrap.php';

// Voorkom directe browseraanroep
if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit(t('cli.only'));
}

if (in_array('cron', $argv)) {
    $limit = date('Y-m-d', strtotime('-3 months')) . ' 00:00:00';
    // Images moved back to "Niet geverifieerd" get a new 3-month period
    DB::update(
        'images',
        ['archived' => 1],
        '(archived = 0 AND `timestamp` < UNIX_TIMESTAMP(%s))
         OR (archived = 2 AND COALESCE(unverified_since, `timestamp`) < UNIX_TIMESTAMP(%s))',
        $limit,
        $limit
    );
}
$logfile = fopen(__DIR__ . '/cronlog.txt', 'a+');
fwrite($logfile, 'Cron' . (in_array('cron', $argv) ? '' : ' not') . ' run ' . date('Y-m-d H:i:s') . PHP_EOL);
fclose($logfile);
