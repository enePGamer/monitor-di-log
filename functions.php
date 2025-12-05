<?php
// functions.php
require_once __DIR__ . '/config.php';
session_start();

/**
 * Rileva se la richiesta proviene dalla postazione dedicata (kiosk)
 * Attualmente usa IP statico; se vuoi usare header personalizzato o file locale, modifica qui.
 */
function isKiosk(): bool {
    // ATTENZIONE: se il server è dietro proxy, usa HTTP_X_FORWARDED_FOR se necessario.
    $ip = $_SERVER['REMOTE_ADDR'] ?? '';
    return ($ip === KIOSK_IP);
}

function insertLog(int $uid, int $op_type, int $is_kiosk): void {
    $pdo = getPDO();
    $stmt = $pdo->prepare("INSERT INTO t_log (uid, op_type, timestamp, is_kiosk) VALUES (:uid, :op_type, NOW(), :is_kiosk)");
    $stmt->execute([
        ':uid' => $uid,
        ':op_type' => $op_type,
        ':is_kiosk' => $is_kiosk ? 1 : 0,
    ]);
}

/**
 * Forza logout di un utente (usa quando un nuovo utente effettua login sul kiosk)
 */
function forceLogoutUser(int $uid, int $is_kiosk): void {
    $pdo = getPDO();
    // Inserisci record di logout
    insertLog($uid, 0, $is_kiosk);
    // Aggiorna online flag
    $stmt = $pdo->prepare("UPDATE t_user SET online = 0, last_heartbeat = NULL WHERE uid = :uid");
    $stmt->execute([':uid' => $uid]);
}

/**
 * Ricerca utente che risulta online sulla postazione kiosk (se presente)
 * Ritorna row o null.
 */
function getKioskOnlineUser(): ?array {
    $pdo = getPDO();
    $stmt = $pdo->prepare("SELECT * FROM t_user WHERE online = 1 LIMIT 1");
    $stmt->execute();
    $row = $stmt->fetch();
    return $row ?: null;
}

/**
 * Quando arriva il login sul kiosk: se esiste un altro utente online,
 * e/o il suo last_heartbeat è scaduto, procedi con logout forzato.
 */
function handleKioskPreLogin(int $currentUid): void {
    $other = getKioskOnlineUser();
    $is_kiosk = 1;
    if ($other) {
        $otherUid = (int)$other['uid'];
        if ($otherUid !== $currentUid) {
            // Se utente diverso: registra logout forzato
            forceLogoutUser($otherUid, $is_kiosk);
        }
    }
}

/**
 * Pulisce sessione attiva (helper)
 */
function hLogoutCleanup() {
    $_SESSION = [];
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    session_destroy();
}
