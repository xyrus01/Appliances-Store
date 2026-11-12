<?php
    session_start();
    include("db.php");

    $error='';

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($email === '' || $password === '') {
            $error = "All fields are required";
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
                $error = "Invalid email or password";
            } catch (PDOException $e) {
                error_log($e->getMessage());
                $error = "Something went wrong, please try again";
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <div class="container">
        <div class="log_in">
            <div class="header">
                <img src="" alt="">
                <h3>APPLIANCES STORE</h3>
            </div>
            <h3>WELCOME BACK!</h3>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <h3>LOG IN</h3>

                <label for="email">Email</label>
                <input type="text" name="email" placeholder="Enter your email"> <br>

                <label for="password" placeholder="Enter your password">Password</label>
                <input type="password" name="password"> <br>

                <?php if ($error !== ''): ?>
                    <p><?php echo htmlspecialchars($error); ?></p>
                <?php endif; ?>

                <input type="submit" name="submit" value="Log In">

                <div>
                    <p>Don't have an account? </p>
                    <a href="signup.php"><p><b>Create One</b></p></a>
                </div>
            </form>
        </div>

        <div class="hero">
            <div class="hero_image"></div>
            <h1>EVERYTHING YOU NEED FOR YOUR HOME</h1>
            <p>Explore essential appliances designed to make everyday living easier.</p>
        </div>
    </div>

</body>
</html>

<?php

?>