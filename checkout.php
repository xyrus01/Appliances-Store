<?php
session_start();

// Only logged-in users can check out
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require 'db.php';

// 1. Read the user's cart (same query idea as cart.php)
$sql = "SELECT c.quantity,
               p.name, p.price, p.image
        FROM cart_items c
        JOIN products p ON c.product_id = p.id
        WHERE c.user_id = ?
        ORDER BY c.id";
$stmt = $pdo->prepare($sql);
$stmt->execute([$_SESSION['user_id']]);
$items = $stmt->fetchAll();

// Nothing to check out? Send the user back to the cart.
if (empty($items)) {
    header('Location: cart.php');
    exit;
}

// 2. Totals, always calculated from database prices
$shippingFee = 32;   // must match the fee in cart.php

$itemCount = 0;
$subtotal  = 0;
foreach ($items as $item) {
    $itemCount += $item['quantity'];
    $subtotal  += $item['price'] * $item['quantity'];
}
$total = $subtotal + $shippingFee;

// 3. Payment options (the key is what gets submitted, the rest is display text)
$paymentOptions = [
    'cod'     => ['Cash on Delivery', 'Pay when your appliance arrives.'],
    'ewallet' => ['E-Wallet',         'Pay using an available supported e-wallet.'],
    'bank'    => ['Bank Transfer',    'Pay through an available bank transfer option.'],
];

// 4. Get the logged-in user's saved info (the id comes from the session)
$stmt = $pdo->prepare("SELECT name, phone, email FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch() ?: [];

// Form values and errors. On the first visit the form starts with the user's
// saved info. The address is not in `users`, so it starts empty.
$old = [
    'full_name' => $user['name']  ?? '',
    'phone'     => $user['phone'] ?? '',
    'email'     => $user['email'] ?? '',
    'address'   => '',
    'payment'   => 'cod',
];
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $old['full_name'] = trim($_POST['full_name'] ?? '');
    $old['phone']     = trim($_POST['phone'] ?? '');
    $old['email']     = trim($_POST['email'] ?? '');
    $old['address']   = trim($_POST['address'] ?? '');
    $old['payment']   = $_POST['payment'] ?? '';

    // Full name
    if ($old['full_name'] === '') {
        $errors['full_name'] = 'Please enter your full name';
    } elseif (mb_strlen($old['full_name']) > 70) {
        $errors['full_name'] = 'Name is too long';
    }

    // Phone: digits only, exactly 11 digits starting with 09 (fits users.phone VARCHAR(11))
    if ($old['phone'] === '') {
        $errors['phone'] = 'Please enter your phone number';
    } elseif (!preg_match('/^09\d{9}$/', $old['phone'])) {
        $errors['phone'] = 'Use 11 digits starting with 09, no spaces';
    }

    // Email
    if ($old['email'] === '') {
        $errors['email'] = 'Please enter your email address';
    } elseif (!filter_var($old['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Please enter a valid email address';
    }

    // Address
    if ($old['address'] === '') {
        $errors['address'] = 'Please enter your delivery address';
    } elseif (mb_strlen($old['address']) > 200) {
        $errors['address'] = 'Address is too long';
    }

    // Payment must be one of the options we offer
    if (!is_string($old['payment']) || !array_key_exists($old['payment'], $paymentOptions)) {
        $errors['payment'] = 'Please choose a payment method';
    }

    // No errors: keep the form values in the session for the confirmation page.
    // Nothing is saved to the database, and prices are NOT stored here.
    if (empty($errors)) {
        $_SESSION['checkout'] = [
            'full_name' => $old['full_name'],
            'phone'     => $old['phone'],
            'email'     => $old['email'],
            'address'   => $old['address'],
            'payment'   => $old['payment'],
        ];
        header('Location: confirmation.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php require 'navbar.php'; ?>

    <div class="cart_page">

        <!-- Title + stepper -->
        <div class="checkout_top">
            <div class="cart_header">
                <h1>Checkout</h1>
                <p class="small">Complete your delivery and payment information to place your order.</p>
            </div>

            <div class="stepper row border">
                <a href="cart.php" class="step done row">
                    <span class="step_circle">&#10003;</span>
                    <p>Shopping Cart</p>
                </a>
                <div class="step_line done"></div>

                <div class="step active row">
                    <span class="step_circle">2</span>
                    <p class="bold">Checkout</p>
                </div>
                <div class="step_line"></div>

                <div class="step row">
                    <span class="step_circle">3</span>
                    <p>Order Confirmation</p>
                </div>
            </div>
        </div>

        <form method="post" action="checkout.php" class="cart_layout">

            <!-- Left: two form cards -->
            <div class="checkout_forms">

                <!-- Delivery Information -->
                <div class="checkout_card border">
                    <div class="card_header">
                        <div class="icon_tile border"></div>
                        <div>
                            <h3 class="bold">Delivery Information</h3>
                            <p class="small">Where should we deliver your appliances?</p>
                        </div>
                    </div>

                    <p class="group_label small bold">PERSONAL DETAILS</p>

                    <div class="field">
                        <label for="full_name" class="bold">Full Name</label>
                        <input type="text" id="full_name" name="full_name"
                               placeholder="Juan Dela Cruz" maxlength="70"
                               value="<?= htmlspecialchars($old['full_name']) ?>"
                               class="<?= isset($errors['full_name']) ? 'invalid' : '' ?>" required>
                        <?php if (isset($errors['full_name'])): ?>
                            <p class="field_error"><?= htmlspecialchars($errors['full_name']) ?></p>
                        <?php endif; ?>
                    </div>

                    <div class="form_row">
                        <div class="field">
                            <label for="phone" class="bold">Phone Number</label>
                            <input type="text" id="phone" name="phone"
                                   placeholder="09XXXXXXXXX" maxlength="11" inputmode="numeric"
                                   pattern="09[0-9]{9}" title="11 digits starting with 09, no spaces"
                                   value="<?= htmlspecialchars($old['phone']) ?>"
                                   class="<?= isset($errors['phone']) ? 'invalid' : '' ?>" required>
                            <?php if (isset($errors['phone'])): ?>
                                <p class="field_error"><?= htmlspecialchars($errors['phone']) ?></p>
                            <?php endif; ?>
                        </div>

                        <div class="field">
                            <label for="email" class="bold">Email Address</label>
                            <input type="email" id="email" name="email"
                                   placeholder="juandelacruz@gmail.com" maxlength="50"
                                   value="<?= htmlspecialchars($old['email']) ?>"
                                   class="<?= isset($errors['email']) ? 'invalid' : '' ?>" required>
                            <?php if (isset($errors['email'])): ?>
                                <p class="field_error"><?= htmlspecialchars($errors['email']) ?></p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <p class="group_label small bold">DELIVERY ADDRESS</p>

                    <div class="field">
                        <label for="address" class="bold">Street, Barangay, City</label>
                        <input type="text" id="address" name="address"
                               placeholder="Enter your complete address" maxlength="200"
                               value="<?= htmlspecialchars($old['address']) ?>"
                               class="<?= isset($errors['address']) ? 'invalid' : '' ?>" required>
                        <?php if (isset($errors['address'])): ?>
                            <p class="field_error"><?= htmlspecialchars($errors['address']) ?></p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="checkout_card border">
                    <div class="card_header">
                        <div class="icon_tile border"></div>
                        <div>
                            <h3 class="bold">Payment Method</h3>
                            <p class="small">Choose how you would like to pay.</p>
                        </div>
                    </div>

                    <div class="payment_options">
                        <?php foreach ($paymentOptions as $key => $option): ?>
                            <label class="payment_option border">
                                <div class="icon_tile border"></div>
                                <div class="payment_text">
                                    <p class="bold"><?= htmlspecialchars($option[0]) ?></p>
                                    <p class="small"><?= htmlspecialchars($option[1]) ?></p>
                                </div>
                                <input type="radio" name="payment" value="<?= $key ?>"
                                       <?= $old['payment'] === $key ? 'checked' : '' ?>>
                            </label>
                        <?php endforeach; ?>
                    </div>

                    <?php if (isset($errors['payment'])): ?>
                        <p class="field_error"><?= htmlspecialchars($errors['payment']) ?></p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Right: order summary -->
            <div class="order_summary border">
                <div class="summary_header">
                    <h3 class="cart_title bold">Order Summary</h3>
                    <p class="count border small bold"><?= $itemCount ?> <?= $itemCount === 1 ? 'item' : 'items' ?></p>
                </div>

                <div class="summary_items">
                    <?php foreach ($items as $item): ?>
                        <div class="summary_item">
                            <div class="icon_tile border">
                                <img src="images/products/<?= htmlspecialchars($item['image']) ?>"
                                     alt="<?= htmlspecialchars($item['name']) ?>">
                            </div>
                            <div class="summary_item_text">
                                <p class="bold"><?= htmlspecialchars($item['name']) ?></p>
                                <p class="small">Qty: <?= (int)$item['quantity'] ?></p>
                            </div>
                            <p class="bold">₱<?= number_format($item['price'] * $item['quantity'], 2) ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="summary_totals">
                    <div class="summary_row">
                        <p>Subtotal</p>
                        <p>₱<?= number_format($subtotal, 2) ?></p>
                    </div>
                    <div class="summary_row">
                        <p>Shipping fee</p>
                        <p>₱<?= number_format($shippingFee, 2) ?></p>
                    </div>
                </div>

                <div class="summary_row total bold">
                    <p>Total</p>
                    <h3>₱<?= number_format($total, 2) ?></h3>
                </div>

                <button type="submit" class="checkout button border">Place Order</button>
                <p class="small">By placing your order, you agree to our terms and conditions</p>
            </div>

        </form>
    </div>
</body>
</html>