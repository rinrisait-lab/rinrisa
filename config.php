<?php
$host = "127.0.0.1";
$db   = "coffee_pos";
$user = "root";
$pass = "NSM@admin";   // ដាក់ DB password របស់អ្នក

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);

    // Show error clearly if something wrong
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    die("Database Connection Failed: " . $e->getMessage());
}

// Start session once here
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
