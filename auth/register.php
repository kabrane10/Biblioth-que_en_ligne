<?php
include '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $email = trim($_POST['email']);
    $mdp = password_hash($_POST['mdp'], PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO lecteurs (nom, prenom, email, mot_de_passe) VALUES (?, ?, ?, ?)");
    try {
        $stmt->execute([$nom, $prenom, $email, $mdp]);
        header('Location: login.php?success=1');
        exit;
    } catch (PDOException $e) {
        $erreur = "Email déjà utilisé.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
    <link rel="stylesheet" href="../assets/css/stye.css">
</head>
<body>
    <div class="container">
        <h2>Inscription</h2>
        <?php if(isset($erreur)) echo "<p style='color:red;'>$erreur</p>"; ?>
        <form method="POST">
            <input type="text" name="nom" placeholder="Nom" required>
            <input type="text" name="prenom" placeholder="Prénom" required>
            <input type="email" name="email" placeholder="Email" required>
            <div class="password-wrapper" style="position: relative;">
               <input type="password" id="mdp" name="mdp" placeholder="Mot de passe" required>
               <span class="toggle-password" onclick="togglePassword()">
                   <svg id="eye-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                       <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                       <circle cx="12" cy="12" r="3" />
                   </svg>
               </span>
            </div>
            <button type="submit" class="btn-primary">S'inscrire</button>
        </form>
        <p><strong>Déjà inscrit ?</strong> <a href="login.php">Se connecter</a></p>
    </div>

    <script src="../assets/js/biblio.js"></script>
</body>
</html>