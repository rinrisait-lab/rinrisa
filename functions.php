<?php
// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Hardcoded users (login system)
$users = [
    'NSM'     => password_hash('NSM@admin', PASSWORD_DEFAULT),
    'cashier' => password_hash('admin@123', PASSWORD_DEFAULT),
     'risa' => password_hash('admin2323', PASSWORD_DEFAULT)
];

// ---------------------
// LOGIN FUNCTIONS
// ---------------------
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
// PRODUCTS (hardcoded or from DB)
// ---------------------
function getProducts() {
    return [
        ['id'=>1, 'name'=>'Tea', 'sell_price'=>2.50],
        ['id'=>2, 'name'=>'Coffee', 'sell_price'=>3.50],
        ['id'=>3, 'name'=>'Coka', 'sell_price'=>0.50],
        ['id'=>4, 'name'=>'Cocoun', 'sell_price'=>1.50],
        ['id'=>5, 'name'=>'Cake', 'sell_price'=>6.50],
    ];
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
    unset($_SESSION['cart']);
}

function getCartProducts() {
    $cartItems = [];
    if (!isset($_SESSION['cart'])) return $cartItems;

    $products = getProducts();
    foreach ($_SESSION['cart'] as $pid => $qty) {
        foreach ($products as $p) {
            if ($p['id'] == $pid) {
                $p['qty'] = $qty;
                $p['total'] = $p['sell_price'] * $qty;
                $cartItems[] = $p;
                break;
            }
        }
    }
    return $cartItems;
}
?>
