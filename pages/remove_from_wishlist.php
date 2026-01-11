<?php
// pages/remove_from_wishlist.php

if (session_status() === PHP_SESSION_NONE) {
    // session_start();
}

include '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}

if (isset($_GET['id_livre'])) {
    $id_livre = intval($_GET['id_livre']);
    $id_lecteur = $_SESSION['user_id'];

    $stmt = $pdo->prepare("DELETE FROM liste_lecture WHERE id_livre = ? AND id_lecteur = ?");
    $stmt->execute([$id_livre, $id_lecteur]);
}

//Message de succès
header('Location: wishlist.php?success=removed');
exit;

header('Location: wishlist.php');
exit;
?>