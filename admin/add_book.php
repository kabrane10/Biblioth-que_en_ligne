<?php
if (session_status() === PHP_SESSION_NONE) {
    // session_start();
}
include '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php?redirect=' . urlencode($_SERVER['REQUEST_URI']));
    exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = $_POST['titre'];
    $auteur = $_POST['auteur'];
    $description = $_POST['description'];
    $maison = $_POST['maison_edition'];
    $exemplaires = intval($_POST['nombre_exemplaire']);

    $stmt = $pdo->prepare("INSERT INTO livres (titre, auteur, description, maison_edition, nombre_exemplaire) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$titre, $auteur, $description, $maison, $exemplaires]);
    header('Location: ./dashboard.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un livre</title>
    <link rel="stylesheet" href="../assets/css/stye.css">
</head>
<body>
    <?php include '../pages/header.php'; ?>
    <div class="container">
        <h2>Ajouter un nouveau livre</h2>
        <form method="POST">
            <input type="text" name="titre" placeholder="Titre" required>
            <input type="text" name="auteur" placeholder="Auteur" required>
            <textarea name="description" placeholder="Description" rows="4"></textarea>
            <input type="text" name="maison_edition" placeholder="Maison d'édition">
            <input type="number" name="nombre_exemplaire" value="1" min="0">
            <button type="submit" class="btn-primary">Ajouter</button>
        </form>
    </div>
</body>
</html>