<?php
// index.php
require_once __DIR__ . '/functions.php';

// SE SESSIONE ATTIVA → vai direttamente alla dashboard
if (!empty($_SESSION['uid'])) {
    header('Location: dashboard.php');
    exit;
}

$isKiosk = isKiosk();
?>
<!doctype html>
<html lang="it">
<head>
    <meta charset="utf-8">
    <title>Monitor di log - Home</title>
</head>
<body>
<h2>Benvenuto</h2>

<p>Questa applicazione permette di registrarsi (solo dalla postazione dedicata), effettuare login e monitorare gli accessi.</p>

<ul>
    <li><a href="login.php">Login</a></li>

    <?php if ($isKiosk): ?>
        <li><a href="register.php">Registrati (solo postazione dedicata)</a></li>
    <?php else: ?>
        <li><em>Registrazione disponibile solo dalla postazione dedicata</em></li>
    <?php endif; ?>
</ul>

</body>
</html>
