<?php
session_start();

define('PRODUCTS_FILE', 'products.json');

// Load products from JSON (always)
function getProducts() {
    if (!file_exists(PRODUCTS_FILE)) {
        file_put_contents(PRODUCTS_FILE, json_encode([]));
    }
    $json = file_get_contents(PRODUCTS_FILE);
    return json_decode($json, true);
}

// Save products to JSON
function saveProducts($products) {
    file_put_contents(PRODUCTS_FILE, json_encode($products, JSON_PRETTY_PRINT));
}

// Add product
function addProduct($name, $price) {
    $products = getProducts();
    $id = empty($products) ? 1 : end($products)['id'] + 1;
    $products[] = ['id'=>$id, 'name'=>$name, 'price'=>$price];
    saveProducts($products);
}

// Cart functions (optional, can use session)
function addToCart($product_id, $qty) {
    if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] += $qty;
    } else {
        $_SESSION['cart'][$product_id] = $qty;
    }
}

function getCartProducts() {
    $cartItems = [];
    if (!isset($_SESSION['cart'])) return $cartItems;
    $products = getProducts();
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

// Login system
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
?>
