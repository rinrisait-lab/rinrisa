<?php
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
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>

<body class="login-body">

<div class="login-card">
    <h2>Login</h2>

    <?php if($error): ?>
        <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="post">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" name="login">Login</button>
    </form>
</div>

</body>
</html>
