<?php
declare(strict_types=1);
session_start();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: admin.php'); exit; }
$csrf = $_POST['csrf'] ?? '';
if (!is_string($csrf) || empty($_SESSION['admin_action_csrf']) || !hash_equals($_SESSION['admin_action_csrf'], $csrf)) {
    http_response_code(403);
    exit('Jeton de sécurité invalide.');
}
unset($_SESSION['admin_id'], $_SESSION['admin_username'], $_SESSION['admin_action_csrf'], $_SESSION['admin_login_csrf']);
session_regenerate_id(true);
header('Location: admin.php'); exit;
