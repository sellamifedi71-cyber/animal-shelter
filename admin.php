<?php
declare(strict_types=1);
session_start();
require __DIR__ . '/config.php';

if (isset($_SESSION['admin_id'])) {
    $roleCheck = $cnx->prepare("SELECT role FROM utilisateur WHERE idu = ?");
    $currentAdminId = (int)$_SESSION['admin_id'];
    $roleCheck->bind_param('i', $currentAdminId);
    $roleCheck->execute();
    $currentAdmin = $roleCheck->get_result()->fetch_assoc();
    if (!$currentAdmin || $currentAdmin['role'] !== 'admin') {
        unset($_SESSION['admin_id'], $_SESSION['admin_username'], $_SESSION['admin_action_csrf']);
    }
}

if (!isset($_SESSION['admin_id'])) {
    if (empty($_SESSION['admin_login_csrf'])) {
        $_SESSION['admin_login_csrf'] = bin2hex(random_bytes(32));
    }
    ?>
    <!doctype html>
    <html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Espace équipe — Fedi Shelter</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
    <main class="container">
        <p><a href="index.html">← Accueil Fedi Shelter</a></p>
        <section class="panel">
            <h1>Espace équipe</h1>
            <p>Connectez-vous avec un compte auquel le rôle administrateur a été attribué.</p>
            <form action="admin_login.php" method="post">
                <input type="hidden" name="csrf" value="<?= h($_SESSION['admin_login_csrf']) ?>">
                <label for="username">Nom d'utilisateur</label>
                <input type="text" id="username" name="username" maxlength="50" autocomplete="username" required>
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" autocomplete="current-password" required>
                <button type="submit">Se connecter</button>
            </form>
        </section>
    </main>
    </body>
    </html>
    <?php
    exit;
}

if (empty($_SESSION['admin_action_csrf'])) {
    $_SESSION['admin_action_csrf'] = bin2hex(random_bytes(32));
}
$stmt = $cnx->prepare(
    "SELECT d.id, d.`date`, u.username, a.Nom, a.type
     FROM demande d
     INNER JOIN utilisateur u ON u.idu = d.idu
     INNER JOIN animal a ON a.ida = d.ida
     WHERE d.decision = 'W'
     ORDER BY d.`date` ASC, d.id ASC"
);
$stmt->execute();
$requests = $stmt->get_result();
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gestion des demandes — Fedi Shelter</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<main class="container">
    <header class="site-header">
        <div class="brand"><span class="brand-mark">F</span><div><h1>Fedi Shelter</h1><p>Espace équipe</p></div></div>
        <form action="admin_logout.php" method="post"><input type="hidden" name="csrf" value="<?= h($_SESSION['admin_action_csrf']) ?>"><button type="submit">Déconnexion</button></form>
    </header>
    <section class="panel">
        <h2>Demandes en attente</h2>
        <p>Bonjour <?= h((string)$_SESSION['admin_username']) ?>. Examinez chaque demande puis acceptez-la ou refusez-la.</p>
        <?php if ($requests->num_rows === 0): ?>
            <p>Aucune demande n'est actuellement en attente.</p>
        <?php else: ?>
            <div class="table-wrap">
                <table>
                    <thead><tr><th>Référence</th><th>Date</th><th>Utilisateur</th><th>Animal</th><th>Décision</th></tr></thead>
                    <tbody>
                    <?php while ($row = $requests->fetch_assoc()): ?>
                        <tr>
                            <td>#<?= (int)$row['id'] ?></td>
                            <td><?= h((string)$row['date']) ?></td>
                            <td><?= h((string)$row['username']) ?></td>
                            <td><?= h((string)$row['Nom']) ?> (<?= h((string)$row['type']) ?>)</td>
                            <td>
                                <form class="decision-form" action="admin_decision.php" method="post">
                                    <input type="hidden" name="csrf" value="<?= h($_SESSION['admin_action_csrf']) ?>">
                                    <input type="hidden" name="request_id" value="<?= (int)$row['id'] ?>">
                                    <button type="submit" name="decision" value="A">Accepter</button>
                                    <button type="submit" name="decision" value="R" class="secondary">Refuser</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </section>
</main>
</body>
</html>
