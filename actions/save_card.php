<?php
require_once __DIR__ . '/../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $card_number = $_POST['card_number'];
    $cardholder_name = $_POST['cardholder_name'];
    $expiry_date = $_POST['expiry_date'];
    $card_type = $_POST['card_type'];
    $nickname = $_POST['nickname'] ?? '';
    $id = $_POST['id'] ?? null;

    if ($id) {
        $stmt = $pdo->prepare("UPDATE cards SET card_number=?, cardholder_name=?, expiry_date=?, card_type=?, nickname=? WHERE id=?");
        $stmt->execute([$card_number, $cardholder_name, $expiry_date, $card_type, $nickname, $id]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO cards (card_number, cardholder_name, expiry_date, card_type, nickname) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$card_number, $cardholder_name, $expiry_date, $card_type, $nickname]);
    }

    header('Location: /wallet.php');
    exit;
}
?>
