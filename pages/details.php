
<?php
// Démarre la session en premier !
if (session_status() === PHP_SESSION_NONE) {
    // session_start();
}

include '../config/database.php'; 

// Maintenant le check voit la session
if (!isset($_SESSION['user_id'])) {
    // Redirection avec redirect pour revenir après connexion
    $current_url = $_SERVER['REQUEST_URI'];
    header('Location: ../auth/connexion.php?redirect=' . urlencode($current_url));
    exit;
}

$id = intval($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM livres WHERE id = ?");
$stmt->execute([$id]);
$livre = $stmt->fetch();

if (!$livre) die("Livre non trouvé.");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($livre['titre']) ?></title>
    <link rel="stylesheet" href="../assets/css/stye.css">
    <style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
</style>
</head>
<body>
    <?php include './header.php'; ?>
    <div class="container book-detail">
    <?php if (!empty($livre['image'])): ?>
        <img src="../<?= htmlspecialchars($livre['image']) ?>" 
             alt="Couverture de <?= htmlspecialchars($livre['titre']) ?>" 
             class="book-cover-large">
    <?php else: ?>
        <div class="no-image-large">Aucune couverture disponible</div>
    <?php endif; ?>
        <h2><?= htmlspecialchars($livre['titre']) ?></h2>
        <p><strong>Auteur :</strong> <?= htmlspecialchars($livre['auteur']) ?></p>
        <p><strong>Description :</strong> <?= nl2br(htmlspecialchars($livre['description'])) ?></p>
        <p><strong>Maison d'édition :</strong> <?= htmlspecialchars($livre['maison_edition']) ?></p>
        <p><strong>Exemplaires disponibles :</strong> <?= $livre['nombre_exemplaire'] ?></p>

        <form action="add_to_wishlist.php" method="POST">
            <input type="hidden" name="id_livre" value="<?= $id ?>">
            <button type="submit" class="btn-ajout">Ajouter à ma liste</button>
        </form>
        <a href="results.php" class="btn-primary">Retour</a>
    </div>
</body>
</html>