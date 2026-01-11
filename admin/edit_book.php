<?php
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

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = trim($_POST['titre']);
    $auteur = trim($_POST['auteur']);
    $description = trim($_POST['description'] ?? '');
    $maison = trim($_POST['maison_edition'] ?? '');
    $exemplaires = intval($_POST['nombre_exemplaire']);

    $image_path = $livre['image']; // Garde l'ancienne par défaut

    // Gestion nouvelle image
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        $file_ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

        if (!in_array($file_ext, $allowed_types)) {
            $message = "Seuls les formats JPG, JPEG, PNG et GIF sont autorisés.";
        } elseif ($_FILES['image']['size'] > 2 * 1024 * 1024) {
            $message = "L'image est trop lourde (max 2MB).";
        } else {
            $slug = strtolower(preg_replace('/[^a-z0-9]+/', '-', $titre));
            $new_filename = $slug . '-' . time() . '.' . $file_ext;
            $target_dir = '../uploads/';
            $target_file = $target_dir . $new_filename;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                // Supprime l'ancienne image si existante
                if ($image_path && file_exists('../' . $image_path)) {
                    unlink('../' . $image_path);
                }
                $image_path = 'uploads/' . $new_filename;
            } else {
                $message = "Erreur lors de l'upload de l'image.";
            }
        }
    }

    if (empty($message)) {
        $stmt = $pdo->prepare("
            UPDATE livres 
            SET titre = ?, auteur = ?, description = ?, maison_edition = ?, nombre_exemplaire = ?, image = ? 
            WHERE id = ?
        ");
        $stmt->execute([$titre, $auteur, $description, $maison, $exemplaires, $image_path, $id]);
        
        header('Location: dashboard.php?success=updated');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier le livre</title>
    <link rel="stylesheet" href="../assets/css/stye.css">
</head>
<body>
    <?php include '../pages/header.php'; ?>
    <div class="container">
        <h2>Modifier : <?= htmlspecialchars($livre['titre']) ?></h2>

        <?php if ($message): ?>
            <p style="color: red; font-weight: bold;"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>

        <?php if ($livre['image']): ?>
            <p>Image actuelle :</p>
            <img src="../<?= $livre['image'] ?>" alt="Couverture actuelle" style="max-width: 180px; margin-bottom: 1rem; border-radius: 8px;">
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <input type="text" name="titre" value="<?= htmlspecialchars($livre['titre']) ?>" required>
            <input type="text" name="auteur" value="<?= htmlspecialchars($livre['auteur']) ?>" required>
            <input type="text" name="maison_edition" value="<?= htmlspecialchars($livre['maison_edition']) ?>">
            <input type="number" name="nombre_exemplaire" value="<?= $livre['nombre_exemplaire'] ?>" min="0" required>
            <textarea name="description" rows="4"><?= htmlspecialchars($livre['description']) ?></textarea>
            
            <label for="image">Nouvelle couverture (laisser vide pour conserver l'actuelle)</label>
            <input type="file" name="image" id="image" accept="image/jpeg,image/png,image/gif">

            <button type="submit" class="btn-primary">Enregistrer les modifications</button>
        </form>
    </div>
</body>
</html>