<?php
session_start();
require 'functions.php';

if (isLoggedIn()) {
    header("Location: index.php");
    exit;
}

$error = '';

if (isset($_POST['login'])) {
    if (login($_POST['username'], $_POST['password'])) {
        header("Location: index.php");
        exit;
    } else {
        $error = "Invalid username or password!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Login - POS</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<h2>Login</h2>
<?php if($error) echo "<p>$error</p>"; ?>
<form method="post">
<input type="text" name="username" required>
<input type="password" name="password" required>
<button name="login">Login</button>
</form>
</body>
</html>
