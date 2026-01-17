<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Définit la base URL de mon projet 
$base_url = '/library_online/';  

// Inclure la connexion BD pour la photo 
require_once __DIR__ . '/../config/database.php';
?>

<header>
    <div class="nav-container">
        <div class="logo">
            <img src="/library_online/assets/images/téléchargement.png" alt="Logo">
            <h1><strong>Bibliothèque <br>Lumière d'Étoiles</strong></h1>
        </div>

        <nav class="main-nav">
            <a href="<?php echo $base_url; ?>index.php">Accueil</a>

            <?php if(isset($_SESSION['user_id'])): ?>
                <a href="<?php echo $base_url; ?>pages/wishlist.php">Ma liste</a>
                <a href="<?php echo $base_url; ?>admin/dashboard.php">Gestion livres</a>

                <!-- Bloc profil aligné à droite sur la même ligne -->
                <div class="profile-wrapper" onclick="toggleProfileMenu()">
                    <?php
                    // Récupère la photo depuis BD
                    $stmt = $pdo->prepare("SELECT photo_profil FROM lecteurs WHERE id = ?");
                    $stmt->execute([$_SESSION['user_id']]);
                    $user = $stmt->fetch();
                    $photo = $user['photo_profil'] ?? null;
                    ?>

                    <?php if ($photo): ?>
                        <img src="/library_online/<?= htmlspecialchars($photo) ?>" alt="Profil" class="profile-icon">
                    <?php else: ?>
                        <div class="profile-placeholder">👤</div>
                    <?php endif; ?>

                    <span class="user-greeting">Bonjour <?= htmlspecialchars($_SESSION['user_nom']) ?></span>
                </div>

                <!-- Menu déroulant (masqué par défaut) -->
                <div id="profile-menu" class="profile-dropdown">
                    <a href="<?php echo $base_url; ?>pages/profile.php">Modifier profil</a>
                    <a href="<?php echo $base_url; ?>auth/logout.php">Déconnexion</a>
                </div>

            <?php else: ?>
                <a href="<?php echo $base_url; ?>auth/connexion.php">Connexion</a>
                <a href="<?php echo $base_url; ?>auth/enregistrer.php">Inscription</a>
            <?php endif; ?>
        </nav>
    </div>
</header>

<!-- JavaScript pour le menu déroulant -->
<script>
    function toggleProfileMenu() {
        const menu = document.getElementById('profile-menu');
        if (menu) {
            menu.classList.toggle('show');
        }
    }

    // Ferme le menu si clic en dehors
    document.addEventListener('click', function(event) {
        const wrapper = document.querySelector('.profile-wrapper');
        const menu = document.getElementById('profile-menu');
        if (wrapper && menu && !wrapper.contains(event.target) && !menu.contains(event.target)) {
            menu.classList.remove('show');
        }
    });
</script>