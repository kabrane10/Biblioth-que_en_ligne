<?php
include '../config/database.php';
if (!isset($_SESSION['user_id'])) header('Location: ../auth/login.php');

$id = intval($_GET['id'] ?? 0);
if ($id > 0) {
    $stmt = $pdo->prepare("DELETE FROM livres WHERE id = ?");
    $stmt->execute([$id]);
}

header('Location: ../admin/dashboard.php');
exit;
?>