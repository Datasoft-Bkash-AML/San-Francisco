<?php
// config.php - Database connection settings
// Primary attempt: MySQL via PDO
$host = 'localhost';
$db   = 'sanfrancisco';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

// --- Admin security settings ---
// Change this to a secure bcrypt hash in production. Use password_hash('yourpass', PASSWORD_DEFAULT)
if (!defined('ADMIN_PASS_HASH')) {
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
$pdo = null;
try {
    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    // Continue to SQLite fallback below
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
        // Both MySQL and SQLite failed — rethrow to surface error
        throw new PDOException('Could not connect to MySQL or initialize SQLite fallback.');
    }
}
