<?php
// ---------------------
// START SESSION
// ---------------------
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ---------------------
// USERS LOGIN SYSTEM
// ---------------------
$users = [
    'NSM'     => password_hash('NSM@admin', PASSWORD_DEFAULT),
    'cashier' => password_hash('admin@123', PASSWORD_DEFAULT),
    'risa'    => password_hash('admin2323', PASSWORD_DEFAULT)
];

function login($username, $password) {
    global $users;
    if (isset($users[$username]) && password_verify($password, $users[$username])) {
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

// ---------------------
// PRODUCTS INITIALIZATION
// ---------------------
if (!isset($_SESSION['products'])) {
    $_SESSION['products'] = [
        ['id'=>1,'name'=>'Tea','price'=>2.50],
        ['id'=>2,'name'=>'Coffee','price'=>3.50],
        ['id'=>3,'name'=>'Coka','price'=>0.50],
        ['id'=>4,'name'=>'Cake','price'=>10.50]
    ];
}

// ---------------------
// ADD PRODUCT FUNCTION
// ---------------------
function addProduct($name, $price) {
    if (!isset($_SESSION['products']) || empty($_SESSION['products'])) {
        $_SESSION['products'] = [];
        $id = 1;
    } else {
        $id = end($_SESSION['products'])['id'] + 1;
    }
    $_SESSION['products'][] = ['id'=>$id, 'name'=>$name, 'price'=>$price];
}

// ---------------------
// CART FUNCTIONS
// ---------------------
function addToCart($product_id, $qty) {
    if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] += $qty;
    } else {
        $_SESSION['cart'][$product_id] = $qty;
    }
}

function clearCart() {
    unset($_SESSION['cart']); // only clear cart, login stays
}

function getCartProducts() {
    $cartItems = [];
    if (!isset($_SESSION['cart'])) return $cartItems;

    $products = $_SESSION['products'];
    foreach ($_SESSION['cart'] as $pid => $qty) {
        foreach ($products as $p) {
            if ($p['id'] == $pid) {
                $p['qty'] = $qty;
                $p['total'] = $p['price'] * $qty;
                $cartItems[] = $p;
                break;
            }
        }
    }
    return $cartItems;
}

// ---------------------
// GET PRODUCTS FUNCTION
// ---------------------
function getProducts() {
    return $_SESSION['products'];
}
?>
