<?php

/**
 * Cron entry point for the price scraper.
 *
 * Called once a day by a server cron job:
 *   /usr/bin/php /path/to/public_html/wp-cron.php >> /path/to/storage/logs/scraper-cron.log 2>&1
 */

define('LARAVEL_START', microtime(true));

require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

echo '[' . date('Y-m-d H:i:s') . '] Starting scraper:run' . PHP_EOL;

$exitCode = $kernel->call('scraper:run');

echo $kernel->output();

echo '[' . date('Y-m-d H:i:s') . '] Finished with exit code ' . $exitCode . PHP_EOL;

exit($exitCode);
