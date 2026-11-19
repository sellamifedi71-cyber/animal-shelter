<?php
declare(strict_types=1);
session_start();
require __DIR__ . '/config.php';

function tracking_token(): string {
    if (empty($_SESSION['tracking_csrf'])) $_SESSION['tracking_csrf'] = bin2hex(random_bytes(32));
    return $_SESSION['tracking_csrf'];
}
function verify_tracking_token(): void {
    $token = $_POST['csrf'] ?? '';
    if (!is_string($token) || empty($_SESSION['tracking_csrf']) || !hash_equals($_SESSION['tracking_csrf'], $token)) {
        http_response_code(419); exit('Formulaire expiré. Rechargez la page.');
    }
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
    verify_tracking_token();
    unset($_SESSION['shelter_user_id'], $_SESSION['shelter_username']);
    $_SESSION['tracking_csrf'] = bin2hex(random_bytes(32));
    header('Location: my_requests.php'); exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    verify_tracking_token();
    $username = trim((string)($_POST['username'] ?? ''));
    $password = (string)($_POST['password'] ?? '');
    if ($username === '' || strlen($username) > 50 || $password === '' || strlen($password) > 200) {
        $error = 'Veuillez saisir un nom d’utilisateur et un mot de passe valides.';
    } else {
        $stmt = $cnx->prepare('SELECT idu, username, pass FROM utilisateur WHERE username = ?');
        $stmt->bind_param('s', $username); $stmt->execute(); $user = $stmt->get_result()->fetch_assoc();
        if (!$user) {
            $error = 'Identifiants incorrects.';
        } else {
            $stored = (string)$user['pass'];
            $info = password_get_info($stored);
            $isHashed = isset($info['algoName']) && $info['algoName'] !== 'unknown';
            $valid = $isHashed ? password_verify($password, $stored) : hash_equals($stored, $password);
            if (!$valid) {
                $error = 'Identifiants incorrects.';
            } else {
                if (!$isHashed) {
                    $hash = password_hash($password, PASSWORD_DEFAULT);
                    $upgrade = $cnx->prepare('UPDATE utilisateur SET pass = ? WHERE idu = ?');
                    $id = (int)$user['idu']; $upgrade->bind_param('si', $hash, $id); $upgrade->execute();
                }
                session_regenerate_id(true);
                $_SESSION['shelter_user_id'] = (int)$user['idu'];
                $_SESSION['shelter_username'] = (string)$user['username'];
                $_SESSION['tracking_csrf'] = bin2hex(random_bytes(32));
                header('Location: my_requests.php'); exit;
            }
        }
    }
}

$requests = null;
if (isset($_SESSION['shelter_user_id'])) {
    $userId = (int)$_SESSION['shelter_user_id'];
    $stmt = $cnx->prepare(
        'SELECT d.id, d.`date`, d.decision, a.Nom, a.type
         FROM demande d INNER JOIN animal a ON a.ida = d.ida
         WHERE d.idu = ? ORDER BY d.`date` DESC, d.id DESC'
    );
    $stmt->bind_param('i', $userId); $stmt->execute(); $requests = $stmt->get_result();
}
$labels = ['W' => 'En attente', 'A' => 'Acceptée', 'R' => 'Refusée'];
?>
<!doctype html><html lang="fr"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Suivi de mes demandes — Fedi Shelter</title><link rel="stylesheet" href="style.css"></head><body>
<main class="container">
<header class="site-header"><div class="brand"><span class="brand-mark">F</span><div><h1>Fedi Shelter</h1><p>Suivi des demandes d'adoption</p></div></div><a href="index.html">Accueil</a></header>
<?php if (!isset($_SESSION['shelter_user_id'])): ?>
<section class="panel"><h2>Retrouver mes demandes</h2><p>Connectez-vous avec votre compte Fedi Shelter pour consulter le statut de vos demandes.</p><?php if ($error !== ''): ?><p class="alert"><?= h($error) ?></p><?php endif; ?>
<form method="post"><input type="hidden" name="csrf" value="<?= h(tracking_token()) ?>"><input type="hidden" name="login" value="1"><label for="username">Nom d’utilisateur</label><input id="username" name="username" maxlength="50" autocomplete="username" required><label for="password">Mot de passe</label><input id="password" name="password" type="password" autocomplete="current-password" required><button type="submit">Consulter mes demandes</button></form></section>
<?php else: ?>
<section class="panel"><div class="panel-heading"><div><h2>Bonjour <?= h((string)$_SESSION['shelter_username']) ?></h2><p>Voici l’historique de vos demandes.</p></div><form method="post"><input type="hidden" name="csrf" value="<?= h(tracking_token()) ?>"><button type="submit" name="logout" value="1" class="secondary">Déconnexion</button></form></div>
<?php if ($requests && $requests->num_rows === 0): ?><p>Vous n’avez pas encore envoyé de demande.</p><?php else: ?><div class="table-wrap"><table><thead><tr><th>Référence</th><th>Date</th><th>Animal</th><th>Type</th><th>Statut</th></tr></thead><tbody><?php while ($row = $requests->fetch_assoc()): ?><tr><td>#<?= (int)$row['id'] ?></td><td><?= h((string)$row['date']) ?></td><td><?= h((string)$row['Nom']) ?></td><td><?= h((string)$row['type']) ?></td><td><?= h($labels[$row['decision']] ?? 'Statut inconnu') ?></td></tr><?php endwhile; ?></tbody></table></div><?php endif; ?>
<p><a href="Av_animals.html">Parcourir les animaux</a> · <a href="add_demande.php">Déposer une autre demande</a></p></section>
<?php endif; ?>
</main></body></html>
