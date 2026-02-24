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
<title>BUTHMAIYA MART - Login</title>

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body{
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    background:linear-gradient(135deg,#eef2f7,#dbe7f3);
    font-family:Arial, sans-serif;
}

/* Main Container */
.container{
    width:900px;
    height:520px;
    background:#fff;
    border-radius:25px;
    display:flex;
    overflow:hidden;
    box-shadow:0 25px 60px rgba(0,0,0,0.15);
}

/* LEFT SIDE */
.left{
    flex:1;
    background:linear-gradient(135deg,#4a90e2,#357abd);
    color:white;
    padding:60px;
    display:flex;
    flex-direction:column;
    justify-content:center;
}

.left h1{
    font-size:40px;
    margin-bottom:15px;
}

.left p{
    font-size:18px;
    opacity:0.9;
}

/* RIGHT SIDE */
.right{
    flex:1;
    padding:60px;
    display:flex;
    flex-direction:column;
    justify-content:center;
}

.right h2{
    font-size:28px;
    margin-bottom:25px;
}

input{
    width:100%;
    padding:14px;
    margin-bottom:18px;
    border-radius:10px;
    border:1px solid #ccc;
    font-size:14px;
    transition:0.2s;
}

input:focus{
    border-color:#4a90e2;
    outline:none;
    box-shadow:0 0 0 2px rgba(74,144,226,0.2);
}

button{
    padding:14px;
    border-radius:10px;
    border:none;
    background:linear-gradient(135deg,#4a90e2,#357abd);
    color:white;
    font-weight:bold;
    cursor:pointer;
    font-size:15px;
    transition:0.2s;
}

button:hover{
    opacity:0.9;
}

.error{
    background:#ffe5e5;
    color:#b30000;
    padding:10px;
    border-radius:8px;
    margin-bottom:15px;
    font-size:14px;
}

</style>
</head>

<body>

<div class="container">

    <div class="left">
        <h1>BUTHMAIYA MART</h1>
        <p>POS & Inventory Management System</p>
    </div>

    <div class="right">

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
