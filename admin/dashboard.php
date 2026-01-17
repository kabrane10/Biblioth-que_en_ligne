<?php
include '../config/database.php';
if (!isset($_SESSION['user_id'])) header('Location: ../auth/conexion.php');

$stmt = $pdo->query("SELECT * FROM livres ORDER BY titre");
$livres = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des livres</title>
    <link rel="stylesheet" href="../assets/css/stye.css">
    <style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
</style>
</head>
<body>
    <?php include '../pages/header.php'; ?>
    <div class="container">
        <h2>Gestion de la collection</h2>
        <p><a href="./add_book.php" class="btn-primary">+ Ajouter un nouveau livre</a></p>

        <h3 style="margin-top: 10px;">Liste des Livres disponibles</h3>

        <table class="admin-table">
            <thead>
                <tr><th>Image</th><th>Titre</th><th>Auteur</th><th>Actions</th></tr>
            </thead>
            <tbody>
                <?php foreach($livres as $l): ?>
                <tr>
                    <td>
                        <?php if($l['image']): ?>
                            <img src="../<?= $l['image'] ?>" style="width:50px; height:auto;">
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($l['titre']) ?></td>
                    <td><?= htmlspecialchars($l['auteur']) ?></td>
                    <td>
                        <a href="./edit_book.php?id=<?= $l['id'] ?>" class="modifier">Modifier</a>
                        <a href="./delete_book.php?id=<?= $l['id'] ?>" class="supprimer" onclick="return confirm(' Vous êtes sûr de supprimer ce Livre ?')">Supprimer</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>