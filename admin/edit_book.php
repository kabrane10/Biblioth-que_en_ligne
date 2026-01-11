<?php
if (session_status() === PHP_SESSION_NONE) {
    // session_start();
}
include '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php?redirect=' . urlencode($_SERVER['REQUEST_URI']));
    exit;
}

$id = intval($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM livres WHERE id = ?");
$stmt->execute([$id]);
$livre = $stmt->fetch();

if (!$livre) die("Livre non trouvé.");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = $_POST['titre'];
    $auteur = $_POST['auteur'];
    $description = $_POST['description'] ?? '';
    $maison = $_POST['maison_edition'] ?? '';
    $exemplaires = intval($_POST['nombre_exemplaire']);

    $stmt = $pdo->prepare("UPDATE livres SET titre = ?, auteur = ?, description = ?, maison_edition = ?, nombre_exemplaire = ? WHERE id = ?");
    $stmt->execute([$titre, $auteur, $description, $maison, $exemplaires, $id]);
    header('Location: dashboard.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier livre</title>
    <link rel="stylesheet" href="../assets/css/stye.css">
</head>
<body>
    <?php include '../pages/header.php'; ?>
    <div class="container">
        <h2>Modifier : <?= htmlspecialchars($livre['titre']) ?></h2>
        <form method="POST">
            <input type="text" name="titre" value="<?= htmlspecialchars($livre['titre']) ?>" required>
            <input type="text" name="auteur" value="<?= htmlspecialchars($livre['auteur']) ?>" required>
            <textarea name="description"><?= htmlspecialchars($livre['description']) ?></textarea>
            <input type="text" name="maison_edition" value="<?= htmlspecialchars($livre['maison_edition']) ?>">
            <input type="number" name="nombre_exemplaire" value="<?= $livre['nombre_exemplaire'] ?>" min="0">
            <button type="submit" class="btn-primary">Enregistrer</button>
        </form>
    </div>
</body>
</html>