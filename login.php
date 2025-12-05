<?php
// login.php
require_once __DIR__ . '/functions.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $errors[] = "Compila tutti i campi.";
    } else {
        $pdo = getPDO();
        $stmt = $pdo->prepare("SELECT * FROM t_user WHERE username = :username LIMIT 1");
        $stmt->execute([':username' => $username]);
        $user = $stmt->fetch();

        if (!$user || !password_verify($password, $user['password'])) {
            $errors[] = "Credenziali non valide.";
        } else {
            $uid = (int)$user['uid'];
            $kiosk = isKiosk() ? 1 : 0;

            if ($kiosk) {
                // Se c'è un altro utente online sul kiosk, forza logout di quello
                handleKioskPreLogin($uid);
                // Segna l'utente corrente online = 1 e last_heartbeat = now
                $stmt = $pdo->prepare("UPDATE t_user SET online = 1, last_heartbeat = NOW() WHERE uid = :uid");
                $stmt->execute([':uid' => $uid]);
            } else {
                // login da altra postazione: non tocchiamo online flag del kiosk
            }

            // Inserisci log di login
            insertLog($uid, 1, $kiosk);

            // Avvia sessione
            session_regenerate_id(true);
            $_SESSION['uid'] = $uid;
            $_SESSION['username'] = $user['username'];
            $_SESSION['is_kiosk'] = $kiosk;

            header('Location: dashboard.php');
            exit;
        }
    }
}
?>
<!doctype html>
<html lang="it">
<head><meta charset="utf-8"><title>Login</title></head>
<body>
<h2>Login</h2>
<?php foreach ($errors as $e): ?><p style="color:red"><?=htmlspecialchars($e)?></p><?php endforeach; ?>
<form method="post" action="login.php">
    <label>Username: <input name="username" required></label><br>
    <label>Password: <input type="password" name="password" required></label><br>
    <button>Accedi</button>
</form>
<p><a href="register.php">Registrati (solo kiosk)</a></p>
</body>
</html>
