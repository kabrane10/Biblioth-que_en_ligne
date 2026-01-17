<?php

if (session_status() === PHP_SESSION_NONE) {
    // session_start();
}

include '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/connexion.php?redirect=' . urlencode($_SERVER['REQUEST_URI']));
    exit;
}

$id_lecteur = $_SESSION['user_id'];
$stmt = $pdo->prepare("
    SELECT l.*, ll.date_emprunt 
    FROM livres l 
    JOIN liste_lecture ll ON l.id = ll.id_livre 
    WHERE ll.id_lecteur = ?
");
$stmt->execute([$id_lecteur]);
$liste = $stmt->fetchAll();

// Récupère le message de succès ou d'erreur via GET
$message = '';
$message_type = ''; // 'success' ou 'error'
if (isset($_GET['success'])) {
    switch ($_GET['success']) {
        case 'added':
            $message = "Livre ajouté à votre liste avec succès !";
            $message_type = 'success';
            break;
        case 'removed':
            $message = "Livre retiré de votre liste avec succès.";
            $message_type = 'success';
            break;
        // Ajoute d'autres cas si besoin
    }
} elseif (isset($_GET['error'])) {
    $message = "Une erreur est survenue. Veuillez réessayer.";
    $message_type = 'error';
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ma liste de lecture</title>
    <link rel="stylesheet" href="../assets/css/stye.css">
    <style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
</style>
</head>
<body>
    <?php include '../pages/header.php'; ?>
    <div class="container">
        <h2>Ma liste de lecture (<?= count($liste) ?> livre(s))</h2>
        
        <!-- Affichage du message de succès/erreur -->
        <?php if ($message): ?>
            <p id="flash-message" style="padding: 1rem; border-radius: 8px; font-weight: bold; 
                <?php echo $message_type === 'success' ? 'background: #d4edda; color: #155724;' : 'background: #f8d7da; color: #721c24;'; ?>">
                <?= htmlspecialchars($message) ?>
            </p>
        <?php endif; ?>
        
        <?php if(empty($liste)): ?>
            <p>Votre liste est vide. <a href="../index.php">Chercher des livres</a></p>
        <?php else: ?>
            <ul class="book-list">
               <?php foreach($liste as $item): ?>
                    <li>
                      <!-- Image cliquable vers détails -->
                      <a href="details.php?id=<?= $item['id'] ?>" class="book-cover-link">
                          <?php if (!empty($item['image'])): ?>
                              <img src="../<?= htmlspecialchars($item['image']) ?>" 
                                   alt="Couverture de <?= htmlspecialchars($item['titre']) ?>" 
                                   class="book-cover-small">
                          <?php else: ?>
                              <div class="no-image">Pas de couverture</div>
                          <?php endif; ?>
                        </a>

                      <!-- Infos du livre -->
                        <div class="book-info">
                          <strong><?= htmlspecialchars($item['titre']) ?></strong>
                          <span>par <?= htmlspecialchars($item['auteur']) ?></span>
                          <span class="date-ajout">(Ajouté le <?= $item['date_emprunt'] ?>)</span>
                
                          <!-- Bouton retirer (reste à côté) -->
                          <a href="remove_from_wishlist.php?id_livre=<?= $item['id'] ?>" 
                             class="btn-danger">Retirer</a>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>

    <!-- JavaScript pour faire disparaître le message après 2 secondes -->
    <?php if ($message): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const messageElement = document.getElementById('flash-message');
                if (messageElement) {
                    setTimeout(function() {
                        messageElement.style.display = 'none';
                    }, 2000); // 2000ms = 2 secondes
                }
            });
        </script>
    <?php endif; ?>
</body>
</html>