<?php
// config.php

declare(strict_types=1);

// Con MySQL nel container Docker
define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'mydb');
define('DB_USER', 'root');
define('DB_PASS', 'abcxyz');

// IP della postazione kiosk (usa 0.0.0.0 per accettare tutte le connessioni in test)
define('KIOSK_IP', getenv('KIOSK_IP') ?: '0.0.0.0');

// Opzioni PDO
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

function getPDO(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    }
    return $pdo;
}