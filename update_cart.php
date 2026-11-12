<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require 'db.php';

// Only accept form submissions
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: cart.php');
    exit;
}

$cartId = (int)($_POST['cart_id'] ?? 0);
$action = $_POST['action'] ?? '';
$userId = $_SESSION['user_id'];

if ($action === 'increase') {
    $stmt = $pdo->prepare("UPDATE cart_items SET quantity = quantity + 1
                           WHERE id = ? AND user_id = ?");
    $stmt->execute([$cartId, $userId]);

} elseif ($action === 'decrease') {
    // quantity > 1 so it can never go to 0 or below
    $stmt = $pdo->prepare("UPDATE cart_items SET quantity = quantity - 1
                           WHERE id = ? AND user_id = ? AND quantity > 1");
    $stmt->execute([$cartId, $userId]);

} elseif ($action === 'remove') {
    $stmt = $pdo->prepare("DELETE FROM cart_items WHERE id = ? AND user_id = ?");
    $stmt->execute([$cartId, $userId]);
}

header('Location: cart.php');
exit;