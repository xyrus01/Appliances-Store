<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require 'db.php';

// Read the value from the URL as text
$categoryId = $_GET['category_id'] ?? '';

// Must be a string of exactly 3 letters (KCH, HOM, PER)
if (!is_string($categoryId) || !preg_match('/^[A-Za-z]{3}$/', $categoryId)) {
    header('Location: categories.php');
    exit;
}

// 2. Make sure that category really exists (and get its name for the heading)
$stmt = $pdo->prepare("SELECT name FROM categories WHERE id = ?");
$stmt->execute([$categoryId]);
$category = $stmt->fetch();

if (!$category) {
    header('Location: categories.php');
    exit;
}

// 3. Get the products of that category, with the brand name, ordered by brand
$sql = "SELECT p.id, p.category_id, p.name, p.price, p.image, p.description,
               b.name AS brand_name
        FROM products p
        JOIN brands b ON p.brand_id = b.id
        WHERE p.category_id = ?
        ORDER BY p.name";

$stmt = $pdo->prepare($sql);
$stmt->execute([$categoryId]);
$products = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($category['name']) ?></title>
</head>
<body>
    <?php require 'navbar.php'; ?>

    <div class="product_lists column">
        <h1><?= htmlspecialchars($category['name']) ?> Appliances</h1>

        <?php if (empty($products)): ?>
            <p>No products in this category yet.</p>
        <?php else: ?>

            <div class="con_products">
                <?php foreach ($products as $p): ?>
                    <?php $productCode = sprintf('%s-%03d', $p['category_id'], $p['id']); ?>
                    <div class="product border column">
                        <div class="images column border">
                            <img src="images/products/<?= htmlspecialchars($p['image']) ?>" alt="<?= htmlspecialchars($p['name']) ?>">
                        </div>
                        <p class="bold"><?= htmlspecialchars($p['name']) ?></p>
                        <p class="product_code small"><?= htmlspecialchars($productCode) ?></p>
                        <p class="small"><?= htmlspecialchars($p['description']) ?></p>
                        <h3>₱<?= number_format($p['price'], 2) ?></h3>
                        <form method="post" action="add_to_cart.php">
                            <input type="hidden" name="product_id" value="<?= (int)$p['id'] ?>">
                            <input type="hidden" name="category_id" value="<?= htmlspecialchars($categoryId) ?>">
                            <button type="submit" class="button border">Add to Cart</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>

        <?php endif; ?>
    </div>
</body>
</html>