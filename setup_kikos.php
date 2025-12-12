<?php
// setup_kiosk.php
// IMPORTANTE: Questa pagina va usata UNA SOLA VOLTA sulla postazione kiosk
// Poi va protetta o rimossa per sicurezza
require_once __DIR__ . '/functions.php';

$message = '';
$is_already_kiosk = isKiosk();

// Password di setup per sicurezza (opzionale ma consigliato)
define('SETUP_PASSWORD', getenv('SETUP_PASSWORD') ?: 'setup123');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'] ?? '';
    
    if ($password !== SETUP_PASSWORD) {
        $message = '<p style="color:red">Password di setup non valida.</p>';
    } else {
        $action = $_POST['action'] ?? '';
        
        if ($action === 'enable') {
            setKioskCookie();
            $message = '<p style="color:green">Cookie kiosk impostato! Questa postazione è ora riconosciuta come kiosk.</p>';
            $is_already_kiosk = true;
        } elseif ($action === 'disable') {
            removeKioskCookie();
            $message = '<p style="color:orange">Cookie kiosk rimosso! Questa postazione non è più riconosciuta come kiosk.</p>';
            $is_already_kiosk = false;
        }
    }
}
?>
<!doctype html>
<html lang="it">
<head>
    <meta charset="utf-8">
    <title>Setup Postazione Kiosk</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 600px; margin: 50px auto; padding: 20px; }
        .status { padding: 15px; border-radius: 5px; margin: 20px 0; }
        .status.active { background: #d4edda; border: 1px solid #c3e6cb; }
        .status.inactive { background: #f8d7da; border: 1px solid #f5c6cb; }
        button { padding: 10px 20px; margin: 5px; cursor: pointer; }
        .btn-enable { background: #28a745; color: white; border: none; }
        .btn-disable { background: #dc3545; color: white; border: none; }
        input[type="password"] { padding: 8px; margin: 10px 0; width: 200px; }
        .warning { background: #fff3cd; padding: 15px; border: 1px solid #ffc107; border-radius: 5px; margin: 20px 0; }
    </style>
</head>
<body>
    <h1>Setup Postazione Kiosk</h1>
    
    <div class="warning">
        <strong>⚠️ ATTENZIONE:</strong> Questa pagina va usata solo per configurare la postazione kiosk dedicata.
        Dopo la configurazione, proteggi o rimuovi questa pagina per sicurezza.
    </div>
    
    <div class="status <?= $is_already_kiosk ? 'active' : 'inactive' ?>">
        <strong>Stato attuale:</strong> 
        <?= $is_already_kiosk ? '✓ Questa postazione è riconosciuta come KIOSK' : '✗ Questa postazione NON è riconosciuta come kiosk' ?>
    </div>
    
    <?= $message ?>
    
    <h3>Configurazione</h3>
    <p>Inserisci la password di setup per modificare lo stato del kiosk:</p>
    
    <form method="post" style="margin: 20px 0;">
        <input type="password" name="password" placeholder="Password di setup" required>
        <br>
        <?php if (!$is_already_kiosk): ?>
            <button type="submit" name="action" value="enable" class="btn-enable">
                Abilita come Kiosk
            </button>
        <?php else: ?>
            <button type="submit" name="action" value="disable" class="btn-disable">
                Disabilita Kiosk
            </button>
        <?php endif; ?>
    </form>
    
    <hr>
    <p><small>Password di setup: definita nella variabile d'ambiente SETUP_PASSWORD o in functions.php</small></p>
    <p><a href="index.php">← Torna alla home</a></p>
</body>
</html>