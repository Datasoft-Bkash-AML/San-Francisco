<?php
// Simple file logger for development. Writes timestamped messages to logs/db.log
// Compatible with PHP 7.3. Use environment variable DB_LOG=0 to disable logging.

function sf_log($channel, $message)
{
    $enabled = getenv('DB_LOG');
    if ($enabled !== false && ((int)$enabled) === 0) {
        return false; // logging explicitly disabled
    }

    $logDir = dirname(__DIR__) . '/logs';
    if (!is_dir($logDir)) {
        @mkdir($logDir, 0755, true);
    }
    $file = $logDir . '/db.log';
    $time = date('c');
    $pid = getmypid();
    $line = "[{$time}] [pid:{$pid}] [{$channel}] {$message}" . PHP_EOL;
    // Attempt to write; suppress any warnings to avoid leaking to users
    @file_put_contents($file, $line, FILE_APPEND | LOCK_EX);
    return true;
}
