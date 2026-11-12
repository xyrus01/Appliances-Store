<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require 'db.php';

// Only accept form submissions (POST), not someone typing the address
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: categories.php');
    exit;
}

$productId  = (int)($_POST['product_id'] ?? 0);
$categoryId = $_POST['category_id'] ?? '';

// Make sure the product really exists
$stmt = $pdo->prepare("SELECT id FROM products WHERE id = ?");
$stmt->execute([$productId]);

if ($stmt->fetch()) {
    // New row, or +1 quantity if (user_id, product_id) already exists
    $sql = "INSERT INTO cart_items (user_id, product_id, quantity)
            VALUES (?, ?, 1)
            ON DUPLICATE KEY UPDATE quantity = quantity + 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$_SESSION['user_id'], $productId]);
}

// Go back to the category the user was browsing
if (is_string($categoryId) && preg_match('/^[A-Za-z]{3}$/', $categoryId)) {
    header('Location: products.php?category_id=' . urlencode($categoryId));
} else {
    header('Location: categories.php');
}
exit;