<?php
// Simple runtime check that includes config.php and reports which PDO driver is active.
require_once __DIR__ . '/../config.php';

try {
    $driver = $pdo->getAttribute(PDO::ATTR_DRIVER_NAME);
    $version = null;
    try {
        $version = $pdo->getAttribute(PDO::ATTR_SERVER_VERSION);
    } catch (Exception $e) {
        // Some drivers may not provide server version; ignore.
    }
    echo "PDO driver: " . $driver . PHP_EOL;
    if ($version) echo "Server version: " . $version . PHP_EOL;
    echo "DSN source: " . (strpos($driver, 'sqlite') !== false ? 'local sqlite file' : 'mysql (remote)') . PHP_EOL;
} catch (Exception $e) {
    echo "Error inspecting PDO: " . $e->getMessage() . PHP_EOL;
}
