<?php
require_once __DIR__ . '/../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = $_POST['type'];
    $amount = $_POST['amount'];
    $category = $_POST['category'];
    $recipient_name = $_POST['recipient_name'] ?? '';
    $date = $_POST['date'];
    $notes = $_POST['notes'] ?? '';
    $id = $_POST['id'] ?? null;

    if ($id) {
        $stmt = $pdo->prepare("UPDATE transactions SET type=?, amount=?, category=?, recipient_name=?, transaction_date=?, notes=? WHERE id=?");
        $stmt->execute([$type, $amount, $category, $recipient_name, $date, $notes, $id]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO transactions (type, amount, category, recipient_name, transaction_date, notes) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$type, $amount, $category, $recipient_name, $date, $notes]);
    }

    header('Location: /transactions.php');
    exit;
}
?>
