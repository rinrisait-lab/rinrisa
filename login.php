<?php
session_start();
require 'functions.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (login($username, $password)) {
        header("Location: index.php");
        exit;
    } else {
        $error = "Invalid username or password";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Login - BUTHMAIYA MART</title>
<link rel="stylesheet" href="style.css">
</head>
<body class="login-modern">

<div class="login-container">

    <div class="login-left">
        <h1>BUTHMAIYA MART</h1>
        <p>POS & Inventory Management System</p>
    </div>

    <div class="login-card-modern">

        <h2>Login</h2>

        <?php if($error): ?>
            <div class="error"><?= $error ?></div>
        <?php endif; ?>

        <form method="post">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>

            <button type="submit">Log In</button>
        </form>

    </div>

</div>

</body>
</html>
