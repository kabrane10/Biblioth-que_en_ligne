<?php
include '../config/database.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/connexion.php?redirect=' . urlencode($_SERVER['REQUEST_URI']));
    exit;
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = trim($_POST['titre']);
    $auteur = trim($_POST['auteur']);
    $description = trim($_POST['description'] ?? '');
    $maison = trim($_POST['maison_edition'] ?? '');
    $exemplaires = intval($_POST['nombre_exemplaire']);

    $image_path = null;

    // Gestion de l'upload d'image
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        $file_ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

        if (!in_array($file_ext, $allowed_types)) {
            $message = "Seuls les formats JPG, JPEG, PNG et GIF sont autorisés.";
        } elseif ($_FILES['image']['size'] > 2 * 1024 * 1024) { // Limite 2MB
            $message = "L'image est trop lourde (max 2MB).";
        } else {  
            // Nom d'image basé sur le titre (slugifié)
            $slug = strtolower(preg_replace('/[^a-z0-9]+/', '-', $titre));
            $new_filename = $slug . '-' . time() . '.' . $file_ext; // time() pour éviter collisions
            $target_dir = '../uploads/';
            $target_file = $target_dir . $new_filename;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                $image_path = 'uploads/' . $new_filename;
            } else {
                $message = "Erreur lors de l'upload de l'image.";
            }
        }
    }

    if (empty($message)) {
        $stmt = $pdo->prepare("
            INSERT INTO livres (titre, auteur, description, maison_edition, nombre_exemplaire, image) 
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([$titre, $auteur, $description, $maison, $exemplaires, $image_path]);
        
        header('Location: ./dashboard.php?success=added');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un livre</title>
    <link rel="stylesheet" href="../assets/css/stye.css">
    <style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
</style>
</head>
<body>
    <?php include '../pages/header.php'; ?>
    <div class="container">
        <h2>Ajouter un nouveau livre</h2>

        <?php if ($message): ?>
            <p style="color: red; font-weight: bold;"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">

            <label for="titre">Titre du Livre</label>
            <input type="text" name="titre" placeholder="Titre" required>

            <label for="auteur">Auteur du Livre</label>
            <input type="text" name="auteur" placeholder="Auteur" required>

            <label for="maison_edition">Maison d'édition</label>
            <input type="text" name="maison_edition" placeholder="Maison d'édition">

            <label for="nombre_exemplaire">Nombre d'Exemplaires</label>
            <input type="number" name="nombre_exemplaire" value="1" min="0" required>

            <label for="description">Description du Livre</label>
            <textarea name="description" placeholder="Description" rows="4"></textarea>

            <label for="image">Couverture du livre (JPG, PNG, GIF - max 2MB)</label>
            <input type="file" name="image" id="image" accept="image/jpeg,image/png,image/gif">

            <button type="submit" class="btn-primary">Ajouter le livre</button>
        </form>
    </div>
</body>
</html>