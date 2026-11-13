<?php
session_start();
require 'db.php';

// Form values (remembered after an error) and one error message per field
$old    = ['name' => '', 'phone' => '', 'email' => ''];
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $old['name']  = trim($_POST['name'] ?? '');
    $old['phone'] = trim($_POST['phone'] ?? '');
    $old['email'] = trim($_POST['email'] ?? '');
    $password     = $_POST['password'] ?? '';
    $confirm      = $_POST['confirm_password'] ?? '';

    // Full name (users.name is VARCHAR(70))
    if ($old['name'] === '') {
        $errors['name'] = 'Please enter your full name';
    } elseif (mb_strlen($old['name']) > 70) {
        $errors['name'] = 'Name is too long';
    }

    // Phone: same rule as checkout.php (11 digits starting with 09)
    if ($old['phone'] === '') {
        $errors['phone'] = 'Please enter your phone number';
    } elseif (!preg_match('/^09\d{9}$/', $old['phone'])) {
        $errors['phone'] = 'Use 11 digits starting with 09, no spaces';
    }

    // Email (users.email is VARCHAR(50))
    if ($old['email'] === '') {
        $errors['email'] = 'Please enter your email address';
    } elseif (!filter_var($old['email'], FILTER_VALIDATE_EMAIL) || mb_strlen($old['email']) > 50) {
        $errors['email'] = 'Please enter a valid email address';
    }

    // Password + confirmation
    if ($password === '') {
        $errors['password'] = 'Please create a password';
    } elseif (strlen($password) < 8) {
        $errors['password'] = 'Password must be at least 8 characters';
    }

    if ($confirm === '') {
        $errors['confirm_password'] = 'Please re-enter your password';
    } elseif ($password !== $confirm) {
        $errors['confirm_password'] = 'Passwords do not match';
    }

    // Everything valid: save the user
    if (empty($errors)) {
        $hash = password_hash($password, PASSWORD_DEFAULT);

        try {
            $sql = "INSERT INTO users (name, email, phone, password)
                    VALUES (:name, :email, :phone, :password)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':name'     => $old['name'],
                ':email'    => $old['email'],
                ':phone'    => $old['phone'],
                ':password' => $hash,
            ]);

            // Log the new user in, so categories.php lets them through
            session_regenerate_id(true);
            $_SESSION['user_id']   = (int)$pdo->lastInsertId();
            $_SESSION['user_name'] = $old['name'];

            header('Location: categories.php');
            exit;
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {   // duplicate entry (unique email)
                $errors['email'] = 'That email is already registered';
            } else {
                error_log($e->getMessage());
                $errors['form'] = 'Something went wrong, please try again';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account</title>
    <link rel="stylesheet" href="style.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="auth_page">
    <div class="signup_page">

        <form method="post" action="signup.php" class="auth_card border" novalidate>
            <div class="signup_intro">
                <a href="login.php" class="auth_brand row">
                    <div class="logo border"></div>
                    <h3>APPLIANCES STORE</h3>
                </a>
                <h2 class="auth_title">Create your account</h2>
                <p class="small">Register to start exploring our appliance collection</p>
            </div>

            <div class="form_row">
                <div class="field">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name"
                           placeholder="Enter your full name" maxlength="70"
                           value="<?= htmlspecialchars($old['name']) ?>"
                           class="<?= isset($errors['name']) ? 'invalid' : '' ?>">
                    <?php if (isset($errors['name'])): ?>
                        <p class="field_error"><?= htmlspecialchars($errors['name']) ?></p>
                    <?php endif; ?>
                </div>

                <div class="field">
                    <label for="phone">Phone Number</label>
                    <input type="text" id="phone" name="phone"
                           placeholder="Enter your phone number" maxlength="11" inputmode="numeric"
                           value="<?= htmlspecialchars($old['phone']) ?>"
                           class="<?= isset($errors['phone']) ? 'invalid' : '' ?>">
                    <?php if (isset($errors['phone'])): ?>
                        <p class="field_error"><?= htmlspecialchars($errors['phone']) ?></p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="field">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email"
                       placeholder="Enter your email address" maxlength="50"
                       value="<?= htmlspecialchars($old['email']) ?>"
                       class="<?= isset($errors['email']) ? 'invalid' : '' ?>">
                <?php if (isset($errors['email'])): ?>
                    <p class="field_error"><?= htmlspecialchars($errors['email']) ?></p>
                <?php endif; ?>
            </div>

            <div class="field">
                <label for="password">Password</label>
                <input type="password" id="password" name="password"
                       placeholder="Create a password"
                       class="<?= isset($errors['password']) ? 'invalid' : '' ?>">
                <?php if (isset($errors['password'])): ?>
                    <p class="field_error"><?= htmlspecialchars($errors['password']) ?></p>
                <?php endif; ?>
            </div>

            <div class="field">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password"
                       placeholder="Re-enter your password"
                       class="<?= isset($errors['confirm_password']) ? 'invalid' : '' ?>">
                <?php if (isset($errors['confirm_password'])): ?>
                    <p class="field_error"><?= htmlspecialchars($errors['confirm_password']) ?></p>
                <?php endif; ?>
            </div>

            <?php if (isset($errors['form'])): ?>
                <p class="field_error"><?= htmlspecialchars($errors['form']) ?></p>
            <?php endif; ?>

            <button type="submit" class="auth_button button border">Create Account</button>

            <p class="auth_switch">Already have an account? <a href="login.php">Log in</a></p>
        </form>

    </div>
</body>
</html>