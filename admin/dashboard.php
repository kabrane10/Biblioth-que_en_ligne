<?php
if (session_status() === PHP_SESSION_NONE) {
    // session_start();
}
include '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php?redirect=' . urlencode($_SERVER['REQUEST_URI']));
    exit;
}

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
        
        <h3>Ajouter un livre</h3>
        <form method="POST">
            <input type="text" name="titre" placeholder="Titre" required>
            <input type="text" name="auteur" placeholder="Auteur" required>
            <textarea name="description" placeholder="Description"></textarea>
            <input type="text" name="maison_edition" placeholder="Maison d'édition">
            <input type="number" name="nombre_exemplaire" value="1" min="0">
            <button type="submit" class="btn-primary">Ajouter</button>
        </form>

        <h3>Liste des livres</h3>
        <table>
            <thead><tr><th>Titre</th><th>Auteur</th><th>Actions</th></tr></thead>
            <tbody>
                <?php foreach($livres as $l): ?>
                <tr>
                    <td><?= htmlspecialchars($l['titre']) ?></td>
                    <td><?= htmlspecialchars($l['auteur']) ?></td>
                    <td>
                        <a href="edit_book.php?id=<?= $l['id'] ?>">Modifier</a>
                        <a href="delete_book.php?id=<?= $l['id'] ?>" onclick="return confirm('Supprimer ?')">Supprimer</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Code d'ajout (déplacé en bas pour éviter output avant header)
    $titre = $_POST['titre'];
    $auteur = $_POST['auteur'];
    $description = $_POST['description'] ?? '';
    $maison = $_POST['maison_edition'] ?? '';
    $exemplaires = intval($_POST['nombre_exemplaire']);

    $stmt = $pdo->prepare("INSERT INTO livres (titre, auteur, description, maison_edition, nombre_exemplaire) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$titre, $auteur, $description, $maison, $exemplaires]);
    header('Location: dashboard.php');
    exit;
}
?>