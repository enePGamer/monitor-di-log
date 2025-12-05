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
<head><meta charset="utf-8"><title>Dashboard</title></head>
<body>
<h2>Benvenuto, <?=htmlspecialchars($username)?></h2>
<p>Sei connesso <?= $is_kiosk ? '(postazione dedicata)' : '(altra postazione)' ?></p>
<form method="post" action="logout.php">
    <button>Logout</button>
</form>

<?php if ($is_kiosk): ?>
    <script>
        // includiamo lo script che invia heartbeat e sendBeacon su unload
    </script>
    <script src="assets/kiosk.js"></script>
    <script>
        // Inizializza heartbeat con uid
        Kiosk.init({ uid: <?=json_encode($uid)?>, heartbeatInterval: 15000, logoutUrl: 'logout.php', heartbeatUrl: 'heartbeat.php' });
    </script>
<?php endif; ?>

</body>
</html>
