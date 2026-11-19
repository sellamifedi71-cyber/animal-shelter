<?php
declare(strict_types=1);
require __DIR__ . '/config.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: Av_animals.html'); exit; }
$allowed = ['Chat', 'Chien', 'Cheval'];
$selected = $_POST['types'] ?? [];
if (!is_array($selected)) { http_response_code(422); exit('Types d’animaux invalides.'); }
$selected = array_values(array_unique(array_filter($selected, static fn($type) => is_string($type) && in_array($type, $allowed, true))));
if ($selected === []) { http_response_code(422); exit('Sélectionnez au moins un type d’animal.'); }
$ageEnabled = isset($_POST['max_age_enabled']);
$maxAge = null;
if ($ageEnabled) {
    $rawAge = filter_input(INPUT_POST, 'max_age', FILTER_VALIDATE_INT, ['options' => ['min_range' => 0, 'max_range' => 100]]);
    if ($rawAge === false || $rawAge === null) { http_response_code(422); exit('Indiquez un âge maximum valide.'); }
    $maxAge = $rawAge;
}
$values = array_pad($selected, 3, '__none__');
$ageFlag = $ageEnabled ? 1 : 0;
$ageParam = $maxAge ?? 0;
$stmt = $cnx->prepare('SELECT ida, type, Nom, Age, Race FROM animal WHERE type IN (?, ?, ?) AND (? = 0 OR Age <= ?) ORDER BY type, Age, Nom');
$stmt->bind_param('sssii', $values[0], $values[1], $values[2], $ageFlag, $ageParam);
$stmt->execute();
$animals = $stmt->get_result();
?>
<!doctype html><html lang="fr"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Résultats — Fedi Shelter</title><link rel="stylesheet" href="style.css"></head><body><main class="container"><p><a href="Av_animals.html">← Modifier la recherche</a></p><h1>Animaux correspondant à votre recherche</h1>
<?php if ($animals->num_rows === 0): ?><p>Aucun animal ne correspond à ces critères.</p><?php else: ?><div class="table-wrap"><table class="tinfo"><thead><tr><th>ID</th><th>Type</th><th>Nom</th><th>Âge</th><th>Race</th></tr></thead><tbody><?php while ($row = $animals->fetch_assoc()): ?><tr><td><?= (int)$row['ida'] ?></td><td><?= h($row['type']) ?></td><td><?= h($row['Nom']) ?></td><td><?= (int)$row['Age'] ?></td><td><?= h($row['Race'] ?? '') ?></td></tr><?php endwhile; ?></tbody></table></div><p>Pour demander l'adoption, notez l'identifiant de l'animal puis <a href="add_demande.php">déposez une demande</a>.</p><?php endif; ?><p><a href="index.html">Accueil</a></p></main></body></html>
