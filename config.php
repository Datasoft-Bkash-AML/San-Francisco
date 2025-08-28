<?php
// config.php - Database connection settings
// This file prefers MySQL via PDO but falls back to a local SQLite file.
// It is written to remain compatible with PHP 7.3 (the macOS bundled
// CLI you referenced). No PHP 7.4+ syntax is required.

// ----- Runtime / local overrides -----
// You can set these environment variables to override defaults at runtime:
//   DB_HOST, DB_PORT, DB_NAME, DB_USER, DB_PASS, DB_CHARSET
// Example (macOS/Linux):
//   export DB_HOST=192.168.14.252
//   export DB_NAME=babui_test
//   export DB_USER=sohel
//   export DB_PASS='Remi@123'
// Or set these in your Codespace devcontainer or runtime environment.

$dotenvLoader = __DIR__ . '/tools/dotenv.php';
if (file_exists($dotenvLoader)) {
    // tools/dotenv.php will auto-load a project .env if present
    require_once $dotenvLoader;
}

$loggerFile = __DIR__ . '/tools/logger.php';
if (file_exists($loggerFile)) {
    require_once $loggerFile;
}

// Read from environment with sensible defaults
$host = getenv('DB_HOST') !== false ? getenv('DB_HOST') : 'localhost';
$port = getenv('DB_PORT') !== false ? (int)getenv('DB_PORT') : 3306;
$db   = getenv('DB_NAME') !== false ? getenv('DB_NAME') : 'sanfrancisco';
$user = getenv('DB_USER') !== false ? getenv('DB_USER') : 'root';
$pass = getenv('DB_PASS') !== false ? getenv('DB_PASS') : '';
$charset = getenv('DB_CHARSET') !== false ? getenv('DB_CHARSET') : 'utf8mb4';

// If you explicitly want to force the use of MySQL even when SQLite exists,
// set the env var PREFER_MYSQL=1. Leave unset to allow fallback to SQLite.
$preferMysql = getenv('PREFER_MYSQL') !== false ? (bool)intval(getenv('PREFER_MYSQL')) : false;

// ----- PDO options (compatible with PHP 7.3) -----
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

// --- Admin security settings ---
// Change this to a secure bcrypt hash in production. Use password_hash('yourpass', PASSWORD_DEFAULT)
if (!defined('ADMIN_PASS_HASH')) {
    // Note: password_hash will produce a different hash each run. For a stable
    // dev password you can replace the call with the literal hash string.
    define('ADMIN_PASS_HASH', password_hash('admin123', PASSWORD_DEFAULT));
}

// CSRF helper functions
function csrf_token()
{
    if (session_status() !== PHP_SESSION_ACTIVE) session_start();
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(24));
    }
    return $_SESSION['csrf_token'];
}

function csrf_check($token)
{
    if (session_status() !== PHP_SESSION_ACTIVE) session_start();
    return hash_equals($_SESSION['csrf_token'] ?? '', $token ?? '');
}

// Session hardening
if (session_status() !== PHP_SESSION_ACTIVE) {
    ini_set('session.use_strict_mode', 1);
    session_start();
}

// Attempt MySQL connection; on failure fall back to SQLite
// If the runtime requests we prefer MySQL, skip probing SQLite early.
$pdo = null;
try {
    // Build DSN with optional port
    $mysqlDsn = "mysql:host={$host};port={$port};dbname={$db};charset={$charset}";
    if (function_exists('sf_log')) {
        sf_log('db', "Attempting MySQL connection: host={$host};port={$port};db={$db}");
    }
    if ($preferMysql) {
        // Attempt MySQL and throw on failure so we don't silently use SQLite.
        try {
            $pdo = new PDO($mysqlDsn, $user, $pass, $options);
            if (function_exists('sf_log')) sf_log('db', "MySQL connection successful (forced)");
        } catch (PDOException $e) {
            if (function_exists('sf_log')) sf_log('db', "MySQL connection failed (forced): " . $e->getMessage());
            throw $e; // rethrow to keep previous behavior
        }
    } else {
        // Try MySQL but allow fallback to SQLite if unavailable.
        try {
            $pdo = new PDO($mysqlDsn, $user, $pass, $options);
            if (function_exists('sf_log')) sf_log('db', "MySQL connection successful");
        } catch (PDOException $e) {
            $pdo = null; // will fall through to SQLite below
            if (function_exists('sf_log')) sf_log('db', "MySQL connection failed: " . $e->getMessage());
        }
    }
} catch (PDOException $e) {
    // Continue to SQLite fallback below
    if (function_exists('sf_log')) sf_log('db', "MySQL attempt threw: " . $e->getMessage());
    $pdo = null;
}

if (!$pdo) {
    // Fallback: SQLite local DB in project if MySQL unavailable
    $sqliteFile = __DIR__ . '/data/sanfrancisco.sqlite';
    if (!file_exists(dirname($sqliteFile))) {
        mkdir(dirname($sqliteFile), 0755, true);
    }
    $sqliteDsn = 'sqlite:' . $sqliteFile;
    try {
        $pdo = new PDO($sqliteDsn, null, null, $options);
        if (function_exists('sf_log')) sf_log('db', "SQLite connection successful: {$sqliteFile}");
        // If DB empty, initialize from provided SQL (converted for SQLite)
        $initFlag = dirname($sqliteFile) . '/.initialized';
        if (!file_exists($initFlag)) {
            if (file_exists(__DIR__ . '/database.sql')) {
                $sql = file_get_contents(__DIR__ . '/database.sql');
                // Simple conversion: remove MySQL-specific clauses
                $sql = preg_replace('/AUTO_INCREMENT/mi', '', $sql);
                $sql = preg_replace('/ENGINE=InnoDB[^;]*;/mi', ';', $sql);
                $sql = preg_replace('/`/m', '"', $sql);
                $statements = array_filter(array_map('trim', preg_split('/;\s*\n/', $sql)));
                foreach ($statements as $stmt) {
                    if ($stmt) {
                        try {
                            $pdo->exec($stmt);
                        } catch (Exception $ex) {
                            // ignore individual statement failures during conversion
                        }
                    }
                }
                file_put_contents($initFlag, "initialized");
            }
        }
    } catch (PDOException $e2) {
        // Both MySQL and SQLite failed — log and rethrow to surface error
        if (function_exists('sf_log')) sf_log('db', "SQLite initialization failed: " . $e2->getMessage());
        throw new PDOException('Could not connect to MySQL or initialize SQLite fallback.');
    }
}
