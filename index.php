<?php 

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
 ?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bibliothèque en Ligne</title>
    <link rel="stylesheet" href="./assets/css/stye.css">
</head>
<body>
    <?php require './pages/header.php'; ?>
    <main class="container">
        <h2>Bienvenue !</h2>
        <p>Recherchez et ajoutez vos livres préférés à votre liste de lecture.</p>
        
        <form action="./pages/search.php" method="GET" class="search-form">
            <input type="text" name="query" placeholder="Titre ou auteur..." required>
            <button type="submit">Rechercher</button>
        </form>
    </main>
</body>
</html>