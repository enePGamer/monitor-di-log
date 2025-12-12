<?php
// dashboard.php
require_once __DIR__ . '/functions.php';

if (empty($_SESSION['uid'])) {
    header('Location: login.php');
    exit;
}

$uid = (int)$_SESSION['uid'];
$username = $_SESSION['username'] ?? 'utente';
$is_kiosk = isKiosk() ? 1 : 0;
?>
<!doctype html>
<html lang="it">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>👋 Benvenuto!</h1>
            <p>Dashboard di <?= htmlspecialchars($username) ?></p>
            <?php if ($is_kiosk): ?>
                <span class="badge kiosk">📍 Postazione Kiosk</span>
            <?php else: ?>
                <span class="badge normal">💻 Postazione Standard</span>
            <?php endif; ?>
        </div>

        <div class="dashboard-info">
            <h2>Stato Connessione</h2>
            <p>
                <?php if ($is_kiosk): ?>
                    ✓ Sei connesso dalla postazione kiosk dedicata.<br>
                    Il sistema sta monitorando la tua presenza.
                <?php else: ?>
                    ✓ Sei connesso da una postazione standard.<br>
                    Accesso in modalità visualizzazione.
                <?php endif; ?>
            </p>
        </div>

        <form method="post" action="logout.php" style="margin-top: 24px;">
            <button type="submit" class="btn btn-danger">
                🚪 Logout
            </button>
        </form>

        <div class="links">
            <a href="index.php">← Torna alla home</a>
        </div>
    </div>

    <?php if ($is_kiosk): ?>
        <script src="assets/kiosk.js"></script>
        <script>
            Kiosk.init({ 
                uid: <?= json_encode($uid) ?>, 
                heartbeatInterval: 15000, 
                logoutUrl: 'logout.php', 
                heartbeatUrl: 'heartbeat.php' 
            });
        </script>
    <?php endif; ?>
</body>
</html>