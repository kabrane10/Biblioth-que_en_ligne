
<?php
// Démarre la session en premier !
if (session_status() === PHP_SESSION_NONE) {
    // session_start();
}

include '../config/database.php'; // Ou './config/database.php' selon le dossier

// Maintenant le check voit la session
if (!isset($_SESSION['user_id'])) {
    // Redirection avec redirect pour revenir après connexion
    $current_url = $_SERVER['REQUEST_URI'];
    header('Location: ../auth/login.php?redirect=' . urlencode($current_url));
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
</head>
<body>
    <?php include './header.php'; ?>
    <div class="container book-detail">
        <h2><?= htmlspecialchars($livre['titre']) ?></h2>
        <p><strong>Auteur :</strong> <?= htmlspecialchars($livre['auteur']) ?></p>
        <p><strong>Description :</strong> <?= nl2br(htmlspecialchars($livre['description'])) ?></p>
        <p><strong>Maison d'édition :</strong> <?= htmlspecialchars($livre['maison_edition']) ?></p>
        <p><strong>Exemplaires disponibles :</strong> <?= $livre['nombre_exemplaire'] ?></p>

        <form action="add_to_wishlist.php" method="POST">
            <input type="hidden" name="id_livre" value="<?= $id ?>">
            <button type="submit" class="btn-primary">Ajouter à ma liste</button>
        </form>
        <a href="results.php" class="btn">Retour</a>
    </div>
</body>
</html>