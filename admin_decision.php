<?php
declare(strict_types=1);
session_start();
require __DIR__ . '/config.php';
if (!isset($_SESSION['admin_id'])) { http_response_code(403); exit('Accès réservé à l’équipe.'); }
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: admin.php'); exit; }
$csrf = $_POST['csrf'] ?? '';
if (!is_string($csrf) || empty($_SESSION['admin_action_csrf']) || !hash_equals($_SESSION['admin_action_csrf'], $csrf)) {
    http_response_code(403); exit('Jeton de sécurité invalide. Rechargez la page.');
}
$requestId = filter_input(INPUT_POST, 'request_id', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
$decision = $_POST['decision'] ?? '';
if (!$requestId || !is_string($decision) || !in_array($decision, ['A', 'R'], true)) {
    http_response_code(422); exit('Décision invalide.');
}
$stmt = $cnx->prepare("UPDATE demande SET decision = ? WHERE id = ? AND decision = 'W'");
$stmt->bind_param('si', $decision, $requestId); $stmt->execute();
header('Location: admin.php'); exit;
