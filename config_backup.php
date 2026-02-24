<?php
$host = "127.0.0.1";
$db   = "coffee_pos";
$user = "root";
$pass = "NSM@admin";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("DB Error: " . $e->getMessage());
}
?>
