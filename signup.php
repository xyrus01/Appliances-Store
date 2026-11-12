<?php
    include("db.php");

    $error='';

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $name     = trim($_POST['name'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $phone    = trim($_POST['phone'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($name === '' || $email === '' || $phone === '' || $password === '') {
            $error = "All fields are required";
        } elseif (strlen($password) < 8) {
            $error = "Password must be at least 8 characters";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);

            try {
                $sql = "INSERT INTO users (name, email, phone, password)
                        VALUES (:name, :email, :phone, :password)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':name'     => $name,
                    ':email'    => $email,
                    ':phone'    => $phone,
                    ':password' => $hash,
                ]);
                header('Location: categories.php');
                exit;
            } catch (PDOException $e) {
                if ($e->getCode() == 23000) { // duplicate entry
                    $error = "That email is already registered";
                } else {
                    error_log($e->getMessage());
                    $error = "Something went wrong, please try again";
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
    <title>Document</title>
</head>
<body>
    <div class="header">
        <img src="" alt="">
        <h3>APPLIANCES STORE</h3>
    </div>
    <form method="post" action="<?php htmlspecialchars($_SERVER["PHP_SELF"]) ?>">
        <h2>Create your account</h2>
        <label for="name">Name</label>
        <input type="text" name="name"> <br>

        <label for="email">Email</label>
        <input type="text" name="email"> <br>

        <label for="phone">Phone</label>
        <input type="text" name="phone"> <br>

        <label for="password">Password</label>
        <input type="password" name="password"> <br>

        <?php if ($error !== ''): ?>
            <p><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <input type="submit" name="submit" value="Create Account">
    </form>
</body>
</html>