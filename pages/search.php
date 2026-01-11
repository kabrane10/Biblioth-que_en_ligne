<?php
// search.php
include './config/database.php'; // Démarre la session et connecte à la BD

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['query']) && !empty(trim($_GET['query']))) {
    $query = trim($_GET['query']);
    // Nettoyage basique (htmlspecialchars pour éviter XSS)
    $query_encoded = urlencode($query);
    header("Location: ./results.php?query=" . $query_encoded);
    exit;
} else {
    // Si pas de recherche valide, retour à l'accueil
    header("Location: ../index.php");
    exit;
}
?>