<?php
session_start();
require 'db.php';

// LOGIN
function login($username, $password) {
    global $users;
    if(isset($users[$username]) && password_verify($password, $users[$username])) {
        $_SESSION['user'] = $username;
        return true;
    }
    return false;
}

function isLoggedIn() {
    return isset($_SESSION['user']);
}

function logout() {
    session_destroy();
}

// PRODUCTS
function getProducts() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM products");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// CART
function addToCart($product_id, $qty) {
    if(!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
    if(isset($_SESSION['cart'][$product_id])) $_SESSION['cart'][$product_id] += $qty;
    else $_SESSION['cart'][$product_id] = $qty;
}

function clearCart() { unset($_SESSION['cart']); }

function getCartProducts() {
    global $pdo;
    if(!isset($_SESSION['cart'])) return [];
    $cart = [];
    foreach($_SESSION['cart'] as $pid => $qty) {
        $stmt = $pdo->prepare("SELECT * FROM products WHERE id=?");
        $stmt->execute([$pid]);
        $p = $stmt->fetch(PDO::FETCH_ASSOC);
        if($p) {
            $p['qty'] = $qty;
            $p['total'] = $p['sell_price'] * $qty;
            $cart[] = $p;
        }
    }
    return $cart;
}
?>
