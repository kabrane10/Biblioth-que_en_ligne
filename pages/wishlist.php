<?php
if (session_status() === PHP_SESSION_NONE) {
    // session_start();
}
include '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php?redirect=' . urlencode($_SERVER['REQUEST_URI']));
    exit;
}

$id_lecteur = $_SESSION['user_id'];
$stmt = $pdo->prepare("
    SELECT l.*, ll.date_emprunt 
    FROM livres l 
    JOIN liste_lecture ll ON l.id = ll.id_livre 
    WHERE ll.id_lecteur = ?
");
$stmt->execute([$id_lecteur]);
$liste = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ma liste de lecture</title>
    <link rel="stylesheet" href="../assets/css/stye.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="container">
        <h2>Ma liste de lecture (<?= count($liste) ?> livre(s))</h2>
        <?php if(empty($liste)): ?>
            <p>Votre liste est vide. <a href="../index.php">Chercher des livres</a></p>
        <?php else: ?>
            <ul class="book-list">
                <?php foreach($liste as $item): ?>
                    <li>
                        <strong><?= htmlspecialchars($item['titre']) ?></strong> 
                        par <?= htmlspecialchars($item['auteur']) ?>
                        (Ajouté le <?= $item['date_emprunt'] ?>)
                        <a href="remove_from_wishlist.php?id_livre=<?= $item['id'] ?>" class="btn-danger">Retirer</a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
</body>
</html>