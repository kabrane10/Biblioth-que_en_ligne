<?php
// pages/add_to_wishlist.php

// Démarre la session en premier
if (session_status() === PHP_SESSION_NONE) {
    // session_start();
}

include '../config/database.php';

// Protection : doit être connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}

// Traitement du formulaire POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_livre'])) {
    $id_livre = intval($_POST['id_livre']);
    $id_lecteur = $_SESSION['user_id'];
    $date_emprunt = date('Y-m-d');

    // Vérifie si le livre n'est pas déjà dans la liste (évite doublons)
    $check = $pdo->prepare("SELECT 1 FROM liste_lecture WHERE id_livre = ? AND id_lecteur = ?");
    $check->execute([$id_livre, $id_lecteur]);

    if ($check->rowCount() === 0) {
        $stmt = $pdo->prepare("INSERT INTO liste_lecture (id_livre, id_lecteur, date_emprunt) VALUES (?, ?, ?)");
        $stmt->execute([$id_livre, $id_lecteur, $date_emprunt]);
        
        // Message de succès optionnel (tu peux l'ajouter dans wishlist.php plus tard)
    }

    // Redirection vers la liste de lecture pour voir le résultat
    header('Location: wishlist.php');
    exit;
}

// Si pas de POST valide → retour à l'accueil
header('Location: ../index.php');
exit;
?>