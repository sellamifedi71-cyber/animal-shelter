<?php
declare(strict_types=1);
session_start();
require __DIR__ . '/config.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: add_demande.php'); exit; }
$csrf = $_POST['csrf'] ?? '';
if (!is_string($csrf) || empty($_SESSION['adoption_csrf']) || !hash_equals($_SESSION['adoption_csrf'], $csrf)) { http_response_code(403); exit('Session expirée. Rechargez le formulaire.'); }
$expectedGcd = $_SESSION['adoption_gcd'] ?? null;
$submittedGcd = filter_input(INPUT_POST, 'pgcd', FILTER_VALIDATE_INT);
if (!is_int($expectedGcd) || $submittedGcd === false || $submittedGcd === null || $submittedGcd !== $expectedGcd) { http_response_code(422); exit('Vérification PGCD incorrecte. Rechargez le formulaire et réessayez.'); }
$usernameRaw = $_POST['name'] ?? '';
$password = $_POST['password'] ?? '';
$animalId = filter_input(INPUT_POST, 'animal_id', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
if (!is_string($usernameRaw) || !is_string($password)) { http_response_code(422); exit('Informations invalides.'); }
$username = trim($usernameRaw);
if ($username === '' || strlen($username) > 50 || $password === '' || strlen($password) > 200 || !$animalId) { http_response_code(422); exit('Informations invalides.'); }

$userStmt = $cnx->prepare('SELECT idu, pass FROM utilisateur WHERE username = ?');
$userStmt->bind_param('s', $username); $userStmt->execute(); $user = $userStmt->get_result()->fetch_assoc();
if (!$user) { http_response_code(401); exit('Nom d’utilisateur ou mot de passe incorrect.'); }
$stored = (string)$user['pass'];
$info = password_get_info($stored);
$isHashed = isset($info['algoName']) && $info['algoName'] !== 'unknown';
$validPassword = $isHashed ? password_verify($password, $stored) : hash_equals($stored, $password);
if (!$validPassword) { http_response_code(401); exit('Nom d’utilisateur ou mot de passe incorrect.'); }
$userId = (int)$user['idu'];
if (!$isHashed) {
    $newHash = password_hash($password, PASSWORD_DEFAULT);
    $upgrade = $cnx->prepare('UPDATE utilisateur SET pass = ? WHERE idu = ?');
    $upgrade->bind_param('si', $newHash, $userId); $upgrade->execute();
}
$animalStmt = $cnx->prepare('SELECT ida FROM animal WHERE ida = ?');
$animalStmt->bind_param('i', $animalId); $animalStmt->execute();
if (!$animalStmt->get_result()->fetch_assoc()) { http_response_code(404); exit('Animal introuvable.'); }
$pending = $cnx->prepare("SELECT id FROM demande WHERE idu = ? AND ida = ? AND decision = 'W' LIMIT 1");
$pending->bind_param('ii', $userId, $animalId); $pending->execute();
if ($pending->get_result()->fetch_assoc()) { http_response_code(409); exit('Une demande pour cet animal est déjà en attente.'); }
$insert = $cnx->prepare("INSERT INTO demande (idu, ida, `date`, decision) VALUES (?, ?, CURDATE(), 'W')");
$insert->bind_param('ii', $userId, $animalId);
try {
    $insert->execute();
} catch (mysqli_sql_exception $exception) {
    if ((int)$exception->getCode() === 1062) { http_response_code(409); exit('Une demande pour cet animal est déjà en attente.'); }
    throw $exception;
}
$requestId = (int)$cnx->insert_id;
session_regenerate_id(true);
$_SESSION['shelter_user_id'] = $userId;
$_SESSION['shelter_username'] = $username;
$_SESSION['adoption_csrf'] = bin2hex(random_bytes(32));
?>
<!doctype html><html lang="fr"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Demande envoyée — Fedi Shelter</title><link rel="stylesheet" href="style.css"></head><body><main class="container"><p class="success">Votre demande d'adoption a été enregistrée et est en attente d'examen.</p><p>Référence de la demande : <strong>#<?= $requestId ?></strong></p><p><a href="my_requests.php">Suivre mes demandes</a> · <a href="Av_animals.html">Consulter les animaux</a> · <a href="index.html">Accueil</a></p></main></body></html>
