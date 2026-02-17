<?php
require_once __DIR__ . '/../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $target_amount = $_POST['target_amount'];
    $current_amount = $_POST['current_amount'] ?? 0;
    $target_date = $_POST['target_date'];
    $id = $_POST['id'] ?? null;

    if ($id) {
        $stmt = $pdo->prepare("UPDATE goals SET name=?, target_amount=?, current_amount=?, target_date=? WHERE id=?");
        $stmt->execute([$name, $target_amount, $current_amount, $target_date, $id]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO goals (name, target_amount, current_amount, target_date) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $target_amount, $current_amount, $target_date]);
    }

    header('Location: /goals.php');
    exit;
}
?>
