<?php
    session_start();

    // Only logged-in users can see this page
    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit;
    }

    require 'db.php';

    $categories = $pdo->query("SELECT id, name FROM categories ORDER BY id")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Choose a Category</title>
</head>
<body>
    <?php require 'navbar.php'; ?>

    <div class="categories column">
        <div>
            <h1>Find the Right Appliances for Your Home</h1>
            <p>Explore our appliance categories and discover products that fit your everyday needs</p>
        </div>


        <div class="selection row">
            <div class="cards column border">
                <div class="image row border">
                    <img src="images/kitchen.png">
                </div>
                <div class="text">
                    <h3>Kitchen Appliances</h3>
                </div>
                <a href="products.php?category_id=KCH" class="button border row">
                        <p>Explore</p>
                </a>
            </div>

            <div class="cards column border">
                <div class="image row border">
                    <img src="images/home.png">
                </div>
                <div class="text">
                    <h3>Home Appliances</h3>
                </div>
                <a href="products.php?category_id=HOM" class="button border row">
                        <p>Explore</p>
                </a>
            </div>

            <div class="cards column border">
                <div class="image row border">
                    <img src="images/personal.png">
                </div>
                <div class="text">
                    <h3>Personal Appliances</h3>
                </div>
                <a href="products.php?category_id=PER" class="button border row">
                        <p>Explore</p>
                </a>
            </div>
        </div>

        <p>Choose a category to start shopping</p>
    </div>


</body>
</html>