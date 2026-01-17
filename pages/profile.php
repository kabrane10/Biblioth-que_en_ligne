<?php

if (session_status() === PHP_SESSION_NONE) {
    // session_start();
}

include '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/connexion.php?redirect=' . urlencode($_SERVER['REQUEST_URI']));
    exit;
}

 include './header.php';
$user_id = $_SESSION['user_id'];
$message = '';

// Récupère les infos actuelles de l'utilisateur (pour pré-remplir le formulaire)
$stmt = $pdo->prepare("SELECT * FROM lecteurs WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// Traitement du formulaire UNIQUEMENT si POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
   // Accès sécurisé à $_POST ici seulement
   $nom = trim($_POST['nom'] ?? $user['nom']);
   $prenom = trim($_POST['prenom'] ?? $user['prenom']);
   $email = trim($_POST['email'] ?? $user['email']);
   $mdp = !empty($_POST['mdp']) ? password_hash($_POST['mdp'], PASSWORD_DEFAULT) : $user['mot_de_passe']; 
    $photo_path = $user['photo_profil']; 

    // Gestion upload photo profil (comme avant)
    if (isset($_FILES['photo_profil']) && $_FILES['photo_profil']['error'] === UPLOAD_ERR_OK) {
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        $file_ext = strtolower(pathinfo($_FILES['photo_profil']['name'], PATHINFO_EXTENSION));

        if (!in_array($file_ext, $allowed_types)) {
            $message = "Format d'image non autorisé (JPG, PNG, GIF seulement).";
        } elseif ($_FILES['photo_profil']['size'] > 2 * 1024 * 1024) { 
            $message = "Photo trop lourde (max 2MB).";
        } else {
            $slug = strtolower(preg_replace('/[^a-z0-9]+/', '-', $nom . '-' . $prenom));
            $new_filename = 'profil_' . $slug . '_' . time() . '.' . $file_ext;
            $target_dir = '../uploads/';
            $target_file = $target_dir . $new_filename;

            if (move_uploaded_file($_FILES['photo_profil']['tmp_name'], $target_file)) {

                // Supprime l'ancienne photo si existante
                if ($photo_path && file_exists('../' . $photo_path)) {
                    unlink('../' . $photo_path);
                }
                $photo_path = 'uploads/' . $new_filename;
            } else {
                $message = "Erreur d'upload de la photo.";
            }
        }
    }

    if (empty($message)) {
        $stmt = $pdo->prepare("UPDATE lecteurs SET nom = ?, prenom = ?, email = ?, mot_de_passe = ?, photo_profil = ? WHERE id = ?");
        $stmt->execute([$nom, $prenom, $email, $mdp, $photo_path, $user_id]);

        // Mise à jour session
        $_SESSION['user_nom'] = $nom . ' ' . $prenom;

        $message = "Profil mis à jour avec succès !";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mon Profil</title>
    <link rel="stylesheet" href="../assets/css/stye.css">
    <style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
</style>
</head>
<body>
    <div class="container">
        <h2>Mon Profil</h2>

        <?php if ($message): ?>
            <p style="color: <?= strpos($message, 'succès') !== false ? 'green' : 'red'; ?>; font-weight: bold;"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>

        <!-- Affichage photo actuelle -->
        <?php if ($user['photo_profil']): ?>
            <img src="../<?= htmlspecialchars($user['photo_profil']) ?>" alt="Photo de profil" style="width: 150px; height: 150px; border-radius: 50%; object-fit: cover; margin-bottom: 1rem;">
        <?php else: ?>
            <div style="width: 150px; height: 150px; border-radius: 50%; background: #ddd; display: flex; align-items: center; justify-content: center; margin-bottom: 1rem;">Pas de photo</div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <label>Nom</label>
            <input type="text" name="nom" value="<?= htmlspecialchars($user['nom']) ?>" required>

            <label>Prénom</label>
            <input type="text" name="prenom" value="<?= htmlspecialchars($user['prenom']) ?>" required>

            <label>Email</label>
            <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>

            <label>Nouveau mot de passe </label>
            <input type="password" name="mdp" placeholder="Nouveau mot de passe">

            <label>Photo de profil </label>
            <input type="file" name="photo_profil" accept="image/jpeg,image/png,image/gif">

            <!-- Bouton de validation -->
            <button type="submit" class="btn-primary">Mettre à jour le profil</button>
        </form>
    </div>

    
</body>
</html>