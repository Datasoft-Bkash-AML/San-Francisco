<?php
// Minimal .env loader: parse KEY=VALUE lines and setenv/getenv for use by config.php
// This is intentionally tiny and dependency-free for compatibility with PHP 7.3.
function load_dotenv($path)
{
    if (!file_exists($path) || !is_readable($path)) return false;
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || $line[0] === '#') continue;
        if (strpos($line, '=') === false) continue;
        list($key, $val) = explode('=', $line, 2);
        $key = trim($key);
        $val = trim($val);
        // Remove surrounding quotes
        if ((substr($val, 0, 1) === '"' && substr($val, -1) === '"') || (substr($val, 0, 1) === "'" && substr($val, -1) === "'")) {
            $val = substr($val, 1, -1);
        }
        // Set environment if not already set
        if (getenv($key) === false) {
            putenv("{$key}={$val}");
            $_ENV[$key] = $val;
            $_SERVER[$key] = $val;
        }
    }
    return true;
}

// Auto-load .env from project root if present
$rootEnv = dirname(__DIR__) . '/.env';
if (file_exists($rootEnv)) {
    load_dotenv($rootEnv);
}
