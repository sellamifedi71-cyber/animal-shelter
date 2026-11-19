<?php
declare(strict_types=1);
session_start();
require __DIR__ . '/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: admin.php'); exit; }
$csrf = $_POST['csrf'] ?? '';
if (!is_string($csrf) || empty($_SESSION['admin_login_csrf']) || !hash_equals($_SESSION['admin_login_csrf'], $csrf)) {
    http_response_code(403); exit('Session expirée. Rechargez la page de connexion.');
}
$usernameRaw = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';
if (!is_string($usernameRaw) || !is_string($password)) { http_response_code(422); exit('Identifiants invalides.'); }
$username = trim($usernameRaw);
if ($username === '' || strlen($username) > 50 || $password === '' || strlen($password) > 200) {
    http_response_code(422); exit('Identifiants invalides.');
}
$stmt = $cnx->prepare('SELECT idu, username, pass, role FROM utilisateur WHERE username = ? LIMIT 1');
$stmt->bind_param('s', $username); $stmt->execute(); $user = $stmt->get_result()->fetch_assoc();
if (!$user || $user['role'] !== 'admin') { http_response_code(401); exit('Identifiants administrateur incorrects.'); }
$stored = (string)$user['pass'];
$info = password_get_info($stored);
$isHashed = isset($info['algoName']) && $info['algoName'] !== 'unknown';
$valid = $isHashed ? password_verify($password, $stored) : hash_equals($stored, $password);
if (!$valid) { http_response_code(401); exit('Identifiants administrateur incorrects.'); }
if (!$isHashed) {
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $upgrade = $cnx->prepare('UPDATE utilisateur SET pass = ? WHERE idu = ?');
    $userId = (int)$user['idu']; $upgrade->bind_param('si', $hash, $userId); $upgrade->execute();
}
session_regenerate_id(true);
$_SESSION['admin_id'] = (int)$user['idu'];
$_SESSION['admin_username'] = (string)$user['username'];
$_SESSION['admin_action_csrf'] = bin2hex(random_bytes(32));
unset($_SESSION['admin_login_csrf']);
header('Location: admin.php'); exit;
