<?php
// config.php
declare(strict_types=1);

date_default_timezone_set('Europe/Rome');

define('DB_HOST', getenv('DB_HOST'));
define('DB_NAME', getenv('DB_NAME'));
define('DB_USER', getenv('DB_USER'));
define('DB_PASS', getenv('DB_PASS'));
define('KIOSK_IP', getenv('KIOSK_IP'));

define('KIOSK_IP', '192.168.112.17'); // inserisci qui l'IP della postazione dedicata

// Se il kiosk è identificato in altro modo, modifica isKiosk function in functions.php

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

function getPDO(): PDO {
    static $pdo = null;
    global $options;
    if ($pdo === null) {
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
        $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
    }
    return $pdo;
}
