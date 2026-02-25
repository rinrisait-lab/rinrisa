<?php
session_start();
require 'functions.php';

if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Dashboard - Buthmaiya POS</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<nav class="navbar">
    <div class="nav-left">
        <div class="logo">Buthmaiya POS</div>
        <a href="index.php" class="nav-link active">Home</a>
        <a href="orders.php" class="nav-link">Orders</a>
        <a href="#" class="nav-link">Reports</a>
        <a href="#" class="nav-link">Settings</a>
    </div>

    <div class="nav-right">
        <span class="user">
            <?= htmlspecialchars($_SESSION['user']) ?>
        </span>
        <a href="logout.php" class="logout-btn">Logout</a>
    </div>
</nav>

<div class="dashboard-container">

    <div class="welcome-box">
        <h1>Welcome to Buthmaiya POS</h1>
        <p>Modern & Premium POS System</p>
        <a href="orders.php" class="start-btn">Start New Order</a>
    </div>

    <div class="dashboard-cards">
        <div class="card">
            <h3>Today's Sales</h3>
            <p>$1,250.00</p>
        </div>

        <div class="card">
            <h3>Total Orders</h3>
            <p>42 Orders</p>
        </div>

        <div class="card">
            <h3>Products</h3>
            <p>4 Items</p>
        </div>
    </div>

</div>

</body>
</html>
