<?php

require 'common/bootstrap.php';
if (in_array('cron', $argv)) {
    DB::update('images', ['archived' => 1], '`timestamp`<UNIX_TIMESTAMP(%s)', date('Y-m-d', strtotime('-3 months')) . ' 00:00:00');
}
