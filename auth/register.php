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
            <input type="password" name="mdp" placeholder="Mot de passe" required>
            <button type="submit">S'inscrire</button>
        </form>
        <p>Déjà inscrit ? <a href="login.php">Se connecter</a></p>
    </div>
</body>
</html>