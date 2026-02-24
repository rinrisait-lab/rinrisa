<?php
require 'functions.php';

if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

/* ====== SAMPLE PRODUCTS ====== */
if (!isset($_SESSION['products'])) {
    $_SESSION['products'] = [
        ['id'=>1,'name'=>'Tea','price'=>2.50],
        ['id'=>2,'name'=>'Coffee','price'=>3.50],
        ['id'=>3,'name'=>'Coca Cola','price'=>1.50],
        ['id'=>4,'name'=>'Cake','price'=>5.00]
    ];
}

$products = &$_SESSION['products'];

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

/* ====== ADD TO CART ====== */
if (isset($_POST['product_id'])) {

    $id = (int)$_POST['product_id'];
    $qty = max(1,(int)$_POST['quantity']);

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

/* ====== CLEAR CART ====== */
if (isset($_POST['clear'])) {
    $_SESSION['cart'] = [];
}

$cart = $_SESSION['cart'];
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>POS buthmaiyamart</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<h2>Welcome <?= htmlspecialchars($_SESSION['user']) ?></h2>
<a href="logout.php">Logout</a>

<hr>

<h3>Products</h3>

<?php foreach($products as $p): ?>
<form method="post" style="display:inline-block;margin:10px;">
<strong><?= htmlspecialchars($p['name']) ?></strong><br>
$<?= number_format($p['price'],2) ?><br>
<input type="hidden" name="product_id" value="<?= $p['id'] ?>">
<input type="number" name="quantity" value="1" min="1">
<button type="submit">Add</button>
</form>
<?php endforeach; ?>

<hr>

<?php if($cart): ?>
<h3>Cart</h3>
<table border="1" cellpadding="5">
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

<tr>
<td colspan="3"><strong>Grand Total</strong></td>
<td><strong>$<?= number_format($grand,2) ?></strong></td>
</tr>
</table>

<form method="post">
<button type="submit" name="clear">Clear Cart</button>
</form>

<button onclick="window.print()">Print</button>

<?php endif; ?>

</body>
</html>
