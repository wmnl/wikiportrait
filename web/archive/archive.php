<?php

require __DIR__ . '/../common/bootstrap.php';
if (in_array('cron', $argv)) {
    DB::update('images', ['archived' => 1], '`timestamp`<UNIX_TIMESTAMP(%s)', date('Y-m-d', strtotime('-3 months')) . ' 00:00:00');
}
$logfile = fopen(__DIR__ . '/cronlog.txt', 'a+');
fwrite($logfile, 'Cron' . (in_array('cron', $argv) ? '' : ' not') . ' run ' . date('Y-m-d H:i:s') . PHP_EOL);
fclose($logfile);
