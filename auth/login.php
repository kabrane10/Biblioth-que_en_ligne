<?php
include '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $mdp = $_POST['mdp'];

    $stmt = $pdo->prepare("SELECT * FROM lecteurs WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($mdp, $user['mot_de_passe'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_nom'] = $user['nom'] . ' ' . $user['prenom'];

        // Correction clé : décoder l'URL de redirection + fallback sécurisé
        if (isset($_GET['redirect'])) {
            $redirect = urldecode($_GET['redirect']);
            // Sécurité basique : s'assurer que le redirect est interne (optionnel mais recommandé)
            if (strpos($redirect, '/gr/') === 0 || empty(parse_url($redirect, PHP_URL_HOST))) {
                header("Location: " . $redirect);
                exit;
            }
        }
        
        // Si pas de redirect valide → accueil
        header('Location: ../index.php');
        exit;
    } else {
        $erreur = "Identifiants incorrects ou compte inexistant.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <link rel="stylesheet" href="../assets/css/stye.css">
</head>
<body>
    <div class="container">
        <h2>Connexion</h2>
        <?php if(isset($erreur)): ?>
            <p style="color:red; font-weight:bold;"><?= $erreur ?></p>
        <?php endif; ?>
        <form method="POST">
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
            <button type="submit" class="btn-primary">Se connecter</button>
        </form>
        <p><strong>Pas de compte ?</strong> <a href="register.php">S'inscrire</a></p>
    </div>

    <script src="../assets/js/biblio.js"></script>
</body>
</html>