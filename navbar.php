<?php
require 'db.php';

$currentPage = basename($_SERVER['PHP_SELF']);

// 1. Write the query (? is a placeholder for the user's id)
$stmt = $pdo->prepare("SELECT name FROM users WHERE id = ?");

// 2. Execute it
$stmt->execute([$_SESSION['user_id']]);

// 3. Fetch the row
$u = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<head>
    <link rel="stylesheet" href="style.css">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>
<header class="navbar row">
    <a href="categories.php" class="navbar_brand button row">
        <div class="logo row">
            <img src="images/icons/logo.png">
        </div>
        <h3>Averra Home</h3>
    </a>

    <div class="navbar_links row">
        <p>Hello, <?= htmlspecialchars($u['name'] ?? 'Guest') ?></p>
        <a href="cart.php" class="cart logo row button sm">
            <img src="images/icons/cart.svg">
        </a>

        <a class="logout button row border" href="logout.php"><p>Log out</p></a>
    </div>
</header>