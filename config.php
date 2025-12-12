<?php
// config.php

declare(strict_types=1);

// Con MySQL nel container Docker
define('KIOSK_IP', '192.168.112.17'); // IP della tua postazione kiosk
define('DB_HOST', '127.0.0.1');  // o "localhost"
define('DB_NAME', 'mydb');       // nome definito in init.sql
define('DB_USER', 'root');
define('DB_PASS', 'abcxyz');     // password definita in init.sql

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
