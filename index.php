<?php
session_start();
require 'functions.php';

if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

/* PRODUCTS */
if (!isset($_SESSION['products'])) {
    $_SESSION['products'] = [
        ['id'=>1,'name'=>'Tea','price'=>2.50,'image'=>'images/tea.jpg'],
        ['id'=>2,'name'=>'Coffee','price'=>3.50,'image'=>'images/coffee.jpg'],
        ['id'=>3,'name'=>'Coca Cola','price'=>1.50,'image'=>'images/coke.jpg'],
        ['id'=>4,'name'=>'Cake','price'=>5.00,'image'=>'images/cake.jpg']
    ];
}

$products = $_SESSION['products'];

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

/* ADD TO CART */
if (isset($_POST['product_id'])) {

    $id  = (int)$_POST['product_id'];
    $qty = max(1, (int)$_POST['quantity']);

    foreach ($products as $p) {
        if ($p['id'] == $id) {

            $found = false;

            foreach ($_SESSION['cart'] as &$item) {
                if ($item['id'] == $id) {
                    $item['qty'] += $qty;
                    $item['total'] = $item['qty'] * $item['price'];
                    $found = true;
                    break;
                }
            }

            if (!$found) {
                $_SESSION['cart'][] = [
                    'id'=>$p['id'],
                    'name'=>$p['name'],
                    'price'=>$p['price'],
                    'qty'=>$qty,
                    'total'=>$p['price']*$qty
                ];
            }

            break;
        }
    }
}

/* CLEAR CART */
if (isset($_POST['clear'])) {
    $_SESSION['cart'] = [];
}

$cart = $_SESSION['cart'];
$grand = 0;
foreach($cart as $item){
    $grand += $item['total'];
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Buthmaiya Premium POS</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar">
    <div class="nav-left">
        <div class="logo">Buthmaiya POS</div>
        <a class="nav-link active">Home</a>
        <a class="nav-link">Orders</a>
        <a class="nav-link">Reports</a>
        <a class="nav-link">Settings</a>
    </div>

    <div class="nav-right">
        <span class="user"><?= htmlspecialchars($_SESSION['user']) ?></span>
        <a href="logout.php" class="logout-btn">Logout</a>
    </div>
</nav>

<div class="pos-container">

    <!-- ORDER PANEL -->
    <div class="order-panel">
        <h3>Order Summary</h3>

        <?php if($cart): ?>
            <?php foreach($cart as $item): ?>
                <div class="order-item">
                    <?= htmlspecialchars($item['name']) ?> (x<?= $item['qty'] ?>)
                    <span>$<?= number_format($item['total'],2) ?></span>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <div class="order-total">
            Total: $<?= number_format($grand,2) ?>
        </div>

        <div class="payment-buttons">
            <button class="cash">Cash</button>
            <button class="card">Card</button>

            <a href="invoices.php" target="_blank">
                <button type="button" class="print">Print</button>
            </a>

            <form method="post">
                <button name="clear" class="clear">Clear</button>
            </form>
        </div>
    </div>

    <!-- PRODUCTS -->
    <div class="product-panel">
        <?php foreach($products as $p): ?>
        <div class="product-card">
            <img src="<?= $p['image'] ?>" alt="<?= $p['name'] ?>">

            <div class="product-info">
                <strong><?= htmlspecialchars($p['name']) ?></strong>
                <span>$<?= number_format($p['price'],2) ?></span>
            </div>

            <form method="post">
                <input type="hidden" name="product_id" value="<?= $p['id'] ?>">
                <div class="qty-row">
                    <input type="number" name="quantity" value="1" min="1">
                    <button>Add</button>
                </div>
            </form>
        </div>
        <?php endforeach; ?>
    </div>

</div>

</body>
</html>
