<?php
// register.php
require_once __DIR__ . '/functions.php';

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isKiosk()) {
        $errors[] = "La registrazione è permessa solo dalla postazione dedicata.";
    } else {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        if ($username === '' || $password === '') {
            $errors[] = "Username e password sono obbligatori.";
        } else {
            $pdo = getPDO();
            // Check username unico
            $stmt = $pdo->prepare("SELECT uid FROM t_user WHERE username = :username");
            $stmt->execute([':username' => $username]);
            if ($stmt->fetch()) {
                $errors[] = "Username già esistente.";
            } else {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO t_user (username, password, creation_date, online) VALUES (:username, :password, NOW(), 0)");
                $stmt->execute([':username' => $username, ':password' => $hash]);
                $success = true;
            }
        }
    }
}
?>
<!doctype html>
<html lang="it">
<head><meta charset="utf-8"><title>Registrazione</title></head>
<body>
<h2>Registrazione</h2>
<?php if ($success): ?>
    <p>Registrazione completata. <a href="login.php">Vai al login</a></p>
<?php else: ?>
    <?php foreach ($errors as $e): ?><p style="color:red"><?=htmlspecialchars($e)?></p><?php endforeach; ?>
    <form method="post" action="register.php">
        <label>Username: <input name="username" required></label><br>
        <label>Password: <input type="password" name="password" required></label><br>
        <button>Registrati</button>
    </form>
<?php endif; ?>
</body>
</html>
