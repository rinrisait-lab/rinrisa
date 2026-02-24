<?php
session_start();
require 'functions.php';

if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

/* SAMPLE PRODUCTS */
if (!isset($_SESSION['products'])) {
    $_SESSION['products'] = [
        ['id'=>1,'name'=>'Tea','price'=>2.50],
        ['id'=>2,'name'=>'Coffee','price'=>3.50],
        ['id'=>3,'name'=>'Coca Cola','price'=>1.50],
        ['id'=>4,'name'=>'Cake','price'=>5.00]
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
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>BUTHMAIYA MART POS</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<div class="top-center">
    <h2>Welcome <?= htmlspecialchars($_SESSION['user']) ?></h2>
    <a href="logout.php" class="logout-btn">Logout</a>
</div>

<div class="main-container">

    <!-- LEFT: PRODUCTS -->
    <div class="left-side">
        <h3>Products</h3>
        <div class="products-container">
            <?php foreach($products as $p): ?>
            <div class="product-box">
                <form method="post">
                    <strong><?= htmlspecialchars($p['name']) ?></strong>
                    <div class="price">$<?= number_format($p['price'],2) ?></div>

                    <input type="hidden" name="product_id" value="<?= $p['id'] ?>">
                    <input type="number" name="quantity" value="1" min="1">

                    <button type="submit">Add</button>
                </form>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- RIGHT: CART -->
    <div class="right-side">
        <h3>Cart</h3>

        <?php if($cart): ?>
        <table>
            <tr>
                <th>#</th>
                <th>Product</th>
                <th>Qty</th>
                <th>Total</th>
            </tr>

            <?php 
            $grand = 0;
            foreach($cart as $i=>$item):
            $grand += $item['total'];
            ?>
            <tr>
                <td><?= $i+1 ?></td>
                <td><?= htmlspecialchars($item['name']) ?></td>
                <td><?= $item['qty'] ?></td>
                <td>$<?= number_format($item['total'],2) ?></td>
            </tr>
            <?php endforeach; ?>

            <tr class="grand-total">
                <td colspan="3">Grand Total</td>
                <td>$<?= number_format($grand,2) ?></td>
            </tr>
        </table>

        <div class="actions">
            <a href="invoices.php" target="_blank">
                <button type="button" class="print-btn">Receipt</button>
            </a>

            <form method="post" style="display:inline;">
                <button type="submit" name="clear" class="clear-btn">Clear</button>
            </form>
        </div>

        <?php else: ?>
            <p style="text-align:center;">Cart is empty</p>
        <?php endif; ?>

    </div>

</div>

</body>
</html>
