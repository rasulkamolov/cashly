<?php
require_once __DIR__ . '/../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $stmt = $pdo->prepare("DELETE FROM cards WHERE id = ?");
    $stmt->execute([$id]);

    header('Location: /wallet.php');
    exit;
}
?>
