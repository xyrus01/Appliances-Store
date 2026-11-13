<?php
session_start();

// Only logged-in users can see this page
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require 'db.php';

// XAMPP defaults to UTC, so set the local time zone for the invoice date/time
date_default_timezone_set('Asia/Manila');

$userId   = $_SESSION['user_id'];
$checkout = $_SESSION['checkout'] ?? null;   // saved by checkout.php

// 1. Read the user's cart (same idea as cart.php, plus category_id for the code)
$sql = "SELECT c.quantity,
               p.id AS product_id, p.category_id, p.name, p.price, p.image,
               b.name AS brand_name
        FROM cart_items c
        JOIN products p ON c.product_id = p.id
        JOIN brands b   ON p.brand_id = b.id
        WHERE c.user_id = ?
        ORDER BY c.id";
$stmt = $pdo->prepare($sql);
$stmt->execute([$userId]);
$items = $stmt->fetchAll();

// 2. Guards: nothing to confirm, or the checkout form was never submitted
if (empty($items)) {
    // Happens on refresh: the cart was already emptied (receipt is not saved)
    unset($_SESSION['checkout']);
    header('Location: categories.php');
    exit;
}
if ($checkout === null) {
    header('Location: checkout.php');
    exit;
}

// 3. Totals, always calculated from database prices
$shippingFee = 32;   // must match cart.php and checkout.php

$subtotal = 0;
foreach ($items as $item) {
    $subtotal += $item['price'] * $item['quantity'];
}
$total = $subtotal + $shippingFee;

// 4. Payment labels (same keys as checkout.php)
$paymentLabels = [
    'cod'     => 'Cash on Delivery',
    'ewallet' => 'E-Wallet',
    'bank'    => 'Bank Transfer',
];
$paymentLabel = $paymentLabels[$checkout['payment']] ?? 'Unknown';

$paymentIcons = [
    'cod'     => 'cash.svg',
    'ewallet' => 'ewallet.svg',
    'bank'    => 'bank.svg',
];
$paymentIcon = $paymentIcons[$checkout['payment']] ?? '';

// 5. Invoice details. No orders table exists, so the numbers are generated here.
$suffix    = random_int(100, 999);
$invoiceNo = 'INV-' . date('Ymd') . '-' . $suffix;
$orderNo   = 'ORD-' . date('Ymd') . '-' . $suffix;
$orderDate = date('F j, Y');
$orderTime = date('h:i:s A');

// Show the phone as 09XX XXX XXXX
$phoneShown = preg_replace('/^(\d{4})(\d{3})(\d{4})$/', '$1 $2 $3', $checkout['phone']);

// 6. Everything needed is now in variables, so empty the cart and the saved form.
//    Refreshing this page will send the user away (the receipt is not saved).
$stmt = $pdo->prepare("DELETE FROM cart_items WHERE user_id = ?");
$stmt->execute([$userId]);
unset($_SESSION['checkout']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php require 'navbar.php'; ?>

    <div class="cart_page confirmation_page">

        <!-- Success message -->
        <div class="confirm_top column">
            <div class="success_icon row">
                <img src="images/icons/check.svg" alt="">
            </div>
            <h1 class="bold">Order Confirmation!</h1>
            <?php if ($checkout['payment'] === 'cod'): ?>
                <p>Please pay once you received your order.</p>
            <?php else: ?>
                <p>Your purchase has been successfully processed.</p>
            <?php endif; ?>
        </div>

        <!-- Invoice card -->
        <div class="invoice border">

            <div class="invoice_header row">
                <div class="invoice_brand row">
                    <div class="logo border row">
                        <img src="images/icons/logo.png">
                    </div>
                    <div>
                        <h3 class="bold">Averra Home</h3>
                        <p class="small">Smart Choices. Better Living.</p>
                    </div>
                </div>
                <p class="invoice_badge border small bold">INVOICE</p>
            </div>

            <div class="invoice_info">
                <div class="info_block">
                    <h3 class="bold">Customer Information</h3>
                    <div class="info_row">
                        <p class="small">Name</p>
                        <p><?= htmlspecialchars($checkout['full_name']) ?></p>
                    </div>
                    <div class="info_row">
                        <p class="small">Email</p>
                        <p><?= htmlspecialchars($checkout['email']) ?></p>
                    </div>
                    <div class="info_row">
                        <p class="small">Phone</p>
                        <p><?= htmlspecialchars($phoneShown) ?></p>
                    </div>
                    <div class="info_row">
                        <p class="small">Address</p>
                        <p><?= htmlspecialchars($checkout['address']) ?></p>
                    </div>
                </div>

                <div class="info_block">
                    <h3 class="bold">Invoice Information</h3>
                    <div class="info_row">
                        <p class="small">Invoice No.</p>
                        <p><?= htmlspecialchars($invoiceNo) ?></p>
                    </div>
                    <div class="info_row">
                        <p class="small">Order No.</p>
                        <p><?= htmlspecialchars($orderNo) ?></p>
                    </div>
                    <div class="info_row">
                        <p class="small">Date</p>
                        <p><?= htmlspecialchars($orderDate) ?></p>
                    </div>
                    <div class="info_row">
                        <p class="small">Time</p>
                        <p><?= htmlspecialchars($orderTime) ?></p>
                    </div>
                </div>
            </div>

            <!-- Items table -->
            <div class="invoice_table">
                <div class="table_head table_row border bold">
                    <p>Product Details</p>
                    <p>Code</p>
                    <p>Quantity</p>
                    <p>Total</p>
                </div>

                <?php foreach ($items as $item): ?>
                    <?php $productCode = sprintf('%s-%03d', $item['category_id'], $item['product_id']); ?>
                    <div class="table_item table_row">
                        <div class="table_product row">
                            <div class="icon_tile border">
                                <img src="images/products/<?= htmlspecialchars($item['image']) ?>"
                                     alt="<?= htmlspecialchars($item['name']) ?>">
                            </div>
                            <div>
                                <p><?= htmlspecialchars($item['name']) ?></p>
                                <p class="small"><?= htmlspecialchars($item['brand_name']) ?></p>
                            </div>
                        </div>
                        <p><?= htmlspecialchars($productCode) ?></p>
                        <p><?= (int)$item['quantity'] ?></p>
                        <p class="bold">₱<?= number_format($item['price'] * $item['quantity'], 2) ?></p>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Payment + totals -->
            <div class="invoice_footer">
                <div class="payment_method border row">
                    <div class="icon_tile payment_icon border row">
                        <?php if ($paymentIcon !== ''): ?>
                            <img src="images/icons/<?= htmlspecialchars($paymentIcon) ?>" alt="">
                        <?php endif; ?>
                    </div>
                    <div>
                        <p class="small">Payment Method</p>
                        <p class="bold"><?= htmlspecialchars($paymentLabel) ?></p>
                    </div>
                </div>

                <div class="invoice_totals border">
                    <div class="summary_row">
                        <p class="small">Subtotal</p>
                        <p>₱<?= number_format($subtotal, 2) ?></p>
                    </div>
                    <div class="summary_row">
                        <p class="small">Shipping fee</p>
                        <p>₱<?= number_format($shippingFee, 2) ?></p>
                    </div>
                    <div class="summary_row total bold">
                        <p>Total</p>
                        <h2>₱<?= number_format($total, 2) ?></h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="confirm_actions row">
            <a href="categories.php" class="action_filled button border row">
                <p>Continue Shopping</p>
            </a>
        </div>
    </div>
</body>
</html>