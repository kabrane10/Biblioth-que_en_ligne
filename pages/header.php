

<?php
if (session_status() === PHP_SESSION_NONE) {
    // session_start();
}

// Définit la base URL de ton projet (adapte si ton dossier n'est pas "gr")
$base_url = '/gr/';  // http://localhost/gr/
?>

<header>
    <div class="nav-container">
        <h1>Bibliothèque en Ligne</h1>
        <nav>
            <a href="<?php echo $base_url; ?>index.php">Accueil</a>
            <?php if(isset($_SESSION['user_id'])): ?>
                <a href="<?php echo $base_url; ?>pages/wishlist.php">Ma liste</a>
                <a href="<?php echo $base_url; ?>admin/dashboard.php">Gestion livres</a>
                <span>Bonjour <?= htmlspecialchars($_SESSION['user_nom']) ?></span>
                <a href="<?php echo $base_url; ?>auth/logout.php">Déconnexion</a>
            <?php else: ?>
                <a href="<?php echo $base_url; ?>auth/login.php">Connexion</a>
                <a href="<?php echo $base_url; ?>auth/register.php">Inscription</a>
            <?php endif; ?>
        </nav>
    </div>
</header>