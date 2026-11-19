<?php
declare(strict_types=1);
session_start();
require __DIR__ . '/config.php';
$a = random_int(12, 49); $b = random_int(12, 49);
function gcdValue(int $a, int $b): int { while ($b !== 0) { [$a, $b] = [$b, $a % $b]; } return $a; }
$_SESSION['adoption_csrf'] = bin2hex(random_bytes(32));
$_SESSION['adoption_gcd'] = gcdValue($a, $b);
$animals = $cnx->query('SELECT ida, type, Nom, Age FROM animal ORDER BY type, Nom');
?>
<!doctype html><html lang="fr"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Demande d'adoption — IKODE Shelter</title><link rel="stylesheet" href="style.css"><script src="controle.js" defer></script></head><body><main class="container"><p><a href="index.html">← Accueil</a></p><section class="panel"><h1>Déposer une demande d'adoption</h1><p>Connectez-vous avec votre compte existant. Les demandes sont enregistrées avec le statut « en attente ».</p><form action="request.php" method="post" onsubmit="return main2()"><input type="hidden" name="csrf" value="<?= h($_SESSION['adoption_csrf']) ?>">
<label for="name">Nom d'utilisateur</label><input type="text" id="name" name="name" maxlength="50" autocomplete="username" required>
<label for="password">Mot de passe</label><input type="password" id="password" name="password" autocomplete="current-password" required>
<label for="animal_id">Animal souhaité</label><select id="animal_id" name="animal_id" required><option value="">Choisir un animal</option><?php while ($animal = $animals->fetch_assoc()): ?><option value="<?= (int)$animal['ida'] ?>">#<?= (int)$animal['ida'] ?> — <?= h($animal['type']) ?> — <?= h($animal['Nom']) ?> (<?= (int)$animal['Age'] ?> ans)</option><?php endwhile; ?></select>
<label for="pgcd">Vérification : quel est le PGCD de <?= $a ?> et <?= $b ?> ?</label><input type="number" id="pgcd" name="pgcd" min="1" max="49" required>
<div class="actions"><button type="submit">Envoyer la demande</button><button type="reset" class="secondary">Effacer</button></div></form></section></main></body></html>
