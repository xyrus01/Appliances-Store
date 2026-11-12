<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require 'db.php';

// The user's cart rows, with product and brand details
$sql = "SELECT c.id AS cart_id, c.quantity,
               p.id AS product_id, p.category_id, p.name, p.price, p.image,
               b.name AS brand_name
        FROM cart_items c
        JOIN products p ON c.product_id = p.id
        JOIN brands b   ON p.brand_id = b.id
        WHERE c.user_id = ?
        ORDER BY c.id";
$stmt = $pdo->prepare($sql);
$stmt->execute([$_SESSION['user_id']]);
$items = $stmt->fetchAll();

// Totals are calculated from database prices
$shippingFee = 32;   // fixed shipping, in pesos

$itemCount = 0;
$subtotal  = 0;
foreach ($items as $item) {
    $itemCount += $item['quantity'];
    $subtotal  += $item['price'] * $item['quantity'];
}

// Shipping only applies if there is something to ship
$shipping = empty($items) ? 0 : $shippingFee;
$total    = $subtotal + $shipping;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php require 'navbar.php'; ?>

    <div class="cart_page">
        <div class="cart_header">
            <h1>Shopping Cart</h1>
            <p class="small">Review your selected appliances before proceeding to checkout.</p>
        </div>

        <div class="cart_layout">

            <!-- Left card: cart items -->
            <div class="cart_items border">
                <h3 class="cart_title bold">Cart Items</h3>

                <?php if (empty($items)): ?>
                    <div class="cart_empty row">
                        <div class="image border"></div>
                        <div class="cart_empty_text">
                            <h2>Your Cart is empty.</h2>
                            <p class="small">Looks like you haven't added any appliances yet.</p>
                            <a href="categories.php" class="button row border">Browse Categories</a>
                        </div>
                    </div>
                <?php else: ?>
                    <?php foreach ($items as $item): ?>
                        <?php $productCode = sprintf('%s-%03d', $item['category_id'], $item['product_id']); ?>
                        <div class="cart_item">
                            <div class="cart_item_image border">
                                <img src="images/products/<?= htmlspecialchars($item['image']) ?>"
                                     alt="<?= htmlspecialchars($item['name']) ?>">
                            </div>

                            <div class="cart_item_info">
                                <h3><?= htmlspecialchars($item['name']) ?></h3>
                                <p class="small"><?= htmlspecialchars($productCode) ?></p>
                                <p class="small"><?= htmlspecialchars($item['brand_name']) ?></p>
                                <p class="bold">₱<?= number_format($item['price'], 2) ?></p>

                                <div class="quantity row">
                                    <form method="post" action="update_cart.php">
                                        <input type="hidden" name="cart_id" value="<?= (int)$item['cart_id'] ?>">
                                        <input type="hidden" name="action" value="decrease">
                                        <button type="submit" <?= $item['quantity'] <= 1 ? 'disabled' : '' ?>>-</button>
                                    </form>

                                    <span><?= (int)$item['quantity'] ?></span>

                                    <form method="post" action="update_cart.php">
                                        <input type="hidden" name="cart_id" value="<?= (int)$item['cart_id'] ?>">
                                        <input type="hidden" name="action" value="increase">
                                        <button type="submit">+</button>
                                    </form>
                                </div>
                            </div>

                            <form method="post" action="update_cart.php">
                                <input type="hidden" name="cart_id" value="<?= (int)$item['cart_id'] ?>">
                                <input type="hidden" name="action" value="remove">
                                <button type="submit" class="remove bold">Remove</button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- Right card: order summary -->
            <div class="order_summary border">
                <h3 class="cart_title bold">Order Summary</h3>

                <div class="summary_row">
                    <p>Items (<?= $itemCount ?>)</p>
                    <p>₱<?= number_format($subtotal, 2) ?></p>
                </div>

                <div class="summary_row">
                    <p>Shipping</p>
                    <p>₱<?= number_format($shipping, 2) ?></p>
                </div>

                <div class="summary_row total bold">
                    <p>Total Price</p>
                    <h3>₱<?= number_format($total, 2) ?></h3>
                </div>

                <?php if (empty($items)): ?>
                    <button class="checkout border" disabled>Proceed to Check out</button>
                <?php else: ?>
                    <a href="checkout.php" class="checkout button row border">Proceed to Check out</a>
                <?php endif; ?>
            </div>

        </div>
    </div>
</body>
</html>