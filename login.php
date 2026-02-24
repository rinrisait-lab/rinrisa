<?php
session_start();
require 'config.php';

$error = '';

if (isset($_POST['login'])) {

    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = md5($_POST['password']);

    $query = mysqli_query($conn,
        "SELECT * FROM users WHERE username='$username' AND password='$password'"
    );

    if (mysqli_num_rows($query) > 0) {
        $user = mysqli_fetch_assoc($query);
        $_SESSION['user'] = $user['username'];

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
</head>
<body>

<h2>Login</h2>

<?php if($error) echo "<p style='color:red'>$error</p>"; ?>

<form method="post">
<input type="text" name="username" required>
<input type="password" name="password" required>
<button name="login">Login</button>
</form>

</body>
</html>
