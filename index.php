<?php 

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/config/database.php';
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bibliothèque en Ligne</title>
    <link rel="stylesheet" href="./assets/css/stye.css">
    <style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
</style>

</head>
<body class="body">
    <?php require './pages/header.php'; ?>

    <div class="google-search-container">
    <form action="./pages/search.php" method="GET" class="google-search-form">
        
        <div class="search-input-wrapper">
            <svg class="search-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#9aa0a6" stroke-width="2">
                <circle cx="11" cy="11" r="8"></circle>
                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
            <input 
                type="text" 
                name="query" 
                placeholder="Rechercher un livre, un auteur..." 
                required
                aria-label="Recherche de livres"
            >
        </div>

        <!-- Deux boutons comme Google -->
        <div class="search-buttons">
            <button type="submit" class="google-btn">Recherche</button>
            <button type="button" class="google-btn luck-btn" onclick="alert('Fonction « J\'ai de la chance » juste pour imiter google donc pas implémentée dans cette version de la bibliothèque 😊')">J'ai de la chance</button>
        </div>
    </form>
</div>
<div class="welcome-container">
    <p id="welcome-text">
    Bienvenue <?= isset($_SESSION['user_nom']) ? htmlspecialchars($_SESSION['user_nom']) . ' ' : '' ?>à la Bibliothèque Lumière d'Étoiles.
    Ici, chaque livre est une fenêtre ouverte sur un monde nouveau. Que vous soyez passionné de 
    récits intemporels, curieux d'aventures imaginaires ou en quête de connaissances, notre collection 
    vous attend avec douceur. Prenez le temps de flâner entre les pages, laissez-vous guider par vos envies... 
    votre prochaine lecture préférée est déjà là, quelque part entre ces lignes. Bonne découverte ! 
    </p>
</div>
       
<script>
    // Le texte à faire apparaître progressivement
    const welcomeMessage = "Bienvenue <?= isset($_SESSION['user_nom']) ? htmlspecialchars($_SESSION['user_nom']) . ' ' : '' ?>à la Bibliothèque Lumière d'Étoiles. Ici, chaque livre est une fenêtre ouverte sur un monde nouveau. Que vous soyez passionné de récits intemporels, curieux d'aventures imaginaires ou en quête de connaissances, notre collection vous attend avec douceur. Prenez le temps de flâner entre les pages, laissez-vous guider par vos envies... votre prochaine lecture préférée est déjà là, quelque part entre ces lignes. Bonne découverte !";

    // Vitesse d'écriture (en millisecondes par caractère)
    const typingSpeed = 60; // 60 = assez fluide, 100 = plus lent et théâtral

    // Fonction qui simule l'écriture
    function typeWriter(text, element, speed, callback) {
        let i = 0;
        element.textContent = ""; // On vide au cas où

        function type() {
            if (i < text.length) {
                element.textContent += text.charAt(i);
                i++;
                setTimeout(type, speed);
            } else if (callback) {
                callback(); // Optionnel : action après fin
            }
        }
        type();
    }

    // Lance l'effet dès que la page est chargée
    window.addEventListener('load', function() {
        const welcomeElement = document.getElementById('welcome-text');
        if (welcomeElement) {
            typeWriter(welcomeMessage, welcomeElement, typingSpeed);
        }
    });
</script>
</body>
</html>