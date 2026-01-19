<?php
// Simple .env loader: loads key=value pairs into getenv(), $_ENV and $_SERVER
$envPath = __DIR__ . '/../../.env';
if (!file_exists($envPath)) {
    $envPath = __DIR__ . '/../../.env.example';
}
if (file_exists($envPath)) {
    $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || $line[0] === '#') continue;
        if (strpos($line, '=') === false) continue;
        list($key, $val) = explode('=', $line, 2);
        $key = trim($key);
        $val = trim($val);
        // remove surrounding quotes
        $val = preg_replace('/^\"|\"$/', '', $val);
        $val = preg_replace("/^'|'$/", '', $val);
        if (!array_key_exists($key, $_ENV)) {
            putenv("$key=$val");
            $_ENV[$key] = $val;
            $_SERVER[$key] = $val;
        }
    }
}
?>
