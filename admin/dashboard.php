<?php
include '../config/database.php';
if (!isset($_SESSION['user_id'])) header('Location: ../auth/login.php');

$stmt = $pdo->query("SELECT * FROM livres ORDER BY titre");
$livres = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des livres</title>
    <link rel="stylesheet" href="../assets/css/stye.css">
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
                        <a href="./edit_book.php?id=<?= $l['id'] ?>">Modifier</a>
                        <a href="./delete_book.php?id=<?= $l['id'] ?>" onclick="return confirm('Supprimer ?')">Supprimer</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>