<?php
session_start();
require 'db.php';

$error = '';
$email = '';   // remembered so the field isn't emptied after a failed login

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        $error = 'All fields are required';
    } else {
        try {
            $stmt = $pdo->prepare("SELECT id, name, password FROM users WHERE email = :email");
            $stmt->execute([':email' => $email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                session_regenerate_id(true);
                $_SESSION['user_id']   = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                header('Location: categories.php');
                exit;
            }
            $error = 'Invalid email or password';
        } catch (PDOException $e) {
            error_log($e->getMessage());
            $error = 'Something went wrong, please try again';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In</title>
    <link rel="stylesheet" href="style.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="auth_page">
    <div class="login_page">

        <!-- Left: brand, welcome text, form card -->
        <div class="login_left">
            <a href="login.php" class="auth_brand row">
                <div class="logo border"></div>
                <h3>APPLIANCES STORE</h3>
            </a>

            <div class="login_welcome">
                <h1>WELCOME BACK!</h1>
                <p class="small">Log in to continue to your account.</p>
            </div>

            <form method="post" action="login.php" class="auth_card border" novalidate>
                <h2 class="auth_title">Log In</h2>

                <div class="field">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email"
                           placeholder="Enter your email" maxlength="50"
                           value="<?= htmlspecialchars($email) ?>"
                           class="<?= $error !== '' ? 'invalid' : '' ?>">
                </div>

                <div class="field">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password"
                           placeholder="Enter your password"
                           class="<?= $error !== '' ? 'invalid' : '' ?>">
                    <?php if ($error !== ''): ?>
                        <p class="field_error"><?= htmlspecialchars($error) ?></p>
                    <?php endif; ?>
                </div>

                <button type="submit" class="auth_button button border">Log In</button>

                <p class="auth_switch">Don't have an account? <a href="signup.php">Create one</a></p>
            </form>
        </div>

        <!-- Right: hero panel -->
        <div class="login_right">
            <div class="hero border">
                <div class="hero_image"></div>
                <h1>EVERYTHING YOU NEED FOR YOUR HOME</h1>
                <p>Explore essential appliances designed to make everyday living easier.</p>
            </div>
        </div>

    </div>
</body>
</html>