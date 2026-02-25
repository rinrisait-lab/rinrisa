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

/* CALCULATE TOTAL */
$cart = $_SESSION['cart'];
$grand = 0;
foreach($cart as $item){
    $grand += $item['total'];
}

/* PROCESS PAYMENT */
if (isset($_POST['cash']) || isset($_POST['card'])) {

    if (!empty($_SESSION['cart'])) {

        $payment_type = isset($_POST['cash']) ? 'Cash' : 'Card';

        $invoice = [
            'date' => date("Y-m-d H:i:s"),
            'items' => $_SESSION['cart'],
            'total' => $grand,
            'payment' => $payment_type
        ];

        if (!isset($_SESSION['invoices'])) {
            $_SESSION['invoices'] = [];
        }

        $_SESSION['invoices'][] = $invoice;

        $_SESSION['cart'] = [];

        header("Location: invoices.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Orders - Buthmaiya POS</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<nav class="navbar">
    <div class="nav-left">
        <div class="logo">Buthmaiya POS</div>
        <a href="index.php" class="nav-link">Home</a>
        <a href="orders.php" class="nav-link active">Orders</a>
        <a href="#" class="nav-link">Reports</a>
        <a href="#" class="nav-link">Settings</a>
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

            <form method="post">
                <button type="submit" name="cash" class="cash">Cash</button>
            </form>

            <form method="post">
                <button type="submit" name="card" class="card">Card</button>
            </form>

            <a href="invoices.php" target="_blank">
                <button type="button" class="print">Print</button>
            </a>

            <form method="post">
                <button type="submit" name="clear" class="clear">Clear</button>
            </form>

        </div>
    </div>

    <!-- PRODUCT PANEL -->
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
                    <button type="submit">Add</button>
                </div>
            </form>
        </div>
        <?php endforeach; ?>
    </div>

</div>

</body>
</html>
