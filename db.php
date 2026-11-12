<?php
    $host   = 'localhost';
    $dbname = 'appliances';   // change to your actual database name in phpMyAdmin
    $user   = 'root';               // XAMPP default
    $pass   = '';                   // XAMPP default is empty

    try {
        $pdo = new PDO(
            "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
            $user,
            $pass,
            [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]
        );
    } catch (PDOException $e) {
        die('Database connection failed: ' . $e->getMessage());
    }
?>