<?php
require '../config/database.php';
// Pas besoin d'être connecté pour voir les résultats (mais le header gérera le menu)

$query = isset($_GET['query']) ? trim(urldecode($_GET['query'])) : '';

$stmt = $pdo->prepare("SELECT * FROM livres WHERE titre LIKE :q OR auteur LIKE :q ORDER BY titre");
$stmt->execute(['q' => "%$query%"]);
$livres = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Résultats de recherche</title>
    <link rel="stylesheet" href="../assets/css/stye.css">
</head>
<body>
    <?php include './header.php'; ?>
    <div class="container">
        <h2>Résultats pour "<?= htmlspecialchars($query) ?>" (<?= count($livres) ?> livre(s) trouvé(s))</h2>
        
        <?php if (empty($livres)): ?>
            <p>Aucun livre trouvé. <a href="../index.php">Retour à l'accueil</a></p>
        <?php else: ?>
            <ul class="book-list">
    <?php foreach ($livres as $livre): ?>
        <li>
            <?php if (!empty($livre['image'])): ?>
                <img src="../<?= htmlspecialchars($livre['image']) ?>" 
                     alt="Couverture de <?= htmlspecialchars($livre['titre']) ?>" 
                     class="book-cover-small">
            <?php else: ?>
                <div class="no-image">Pas de couverture</div>
            <?php endif; ?>
            
            <div class="book-info">
                <strong><?= htmlspecialchars($livre['titre']) ?></strong>
                <span>par <?= htmlspecialchars($livre['auteur']) ?></span>
                <a href="details.php?id=<?= $livre['id'] ?>" class="btn">Voir détails</a>
            </div>
        </li>
    <?php endforeach; ?>
</ul>
           
        <?php endif; ?>
        
        <p><a href="../index.php" class="btn">Nouvelle recherche</a></p>
    </div>
</body>
</html>