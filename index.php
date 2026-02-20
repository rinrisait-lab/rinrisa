<?php
session_start();
require 'functions.php';

/* ================= LOGIN CHECK ================= */
if (!isLoggedIn()) {
    $error = '';
    if (isset($_POST['login'])) {
        if (login($_POST['username'], $_POST['password'])) {
            header("Location: index.php");
            exit;
        } else {
            $error = "Invalid username or password!";
        }
    }
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Login - POS System</title>
</head>
<body>
<form method="post">
    <input type="text" name="username" placeholder="Username" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit" name="login">Login</button>
</form>
</body>
</html>
<?php exit; }

/* ================= INIT PRODUCTS & CART ================= */
if (!isset($_SESSION['products'])) {
    $_SESSION['products'] = [
        ['id'=>1, 'name'=>'Tea', 'price'=>2.50],
        ['id'=>2, 'name'=>'Coffee', 'price'=>3.50],
        ['id'=>3, 'name'=>'Coka', 'price'=>0.50]
    ];
}
$products = &$_SESSION['products'];

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}


/* ================== POST LOGIC ================== */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Add product to cart by product block
    if (isset($_POST['add_to_cart'])) {
        $id = (int)$_POST['product_id'];
        $qty = max(1, (int)$_POST['quantity']);
        foreach ($products as $p) {
            if ($p['id'] == $id) {
                $found = false;
                foreach ($_SESSION['cart'] as &$item) {
                    if ($item['id'] == $id) {
                        $item['qty'] += $qty;
                        $item['total'] = $item['price'] * $item['qty'];
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
                        'total'=>$p['price'] * $qty
                    ];
                }
            }
        }
    }

    // Add new product directly to cart
    if (isset($_POST['add_new_product'])) {
        $name = trim($_POST['new_name']);
        $price = (float)$_POST['new_price'];
        $qty = max(1,(int)$_POST['new_qty']);
        $_SESSION['cart'][] = [
            'id'=>time(),
            'name'=>$name,
            'price'=>$price,
            'qty'=>$qty,
            'total'=>$price*$qty
        ];
    }

    // Clear cart
    if (isset($_POST['clear_cart'])) {
        $_SESSION['cart'] = [];
    }
}

$cart = $_SESSION['cart'];
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>POS System BUTHMAIYA</title>
    <style>
        body { font-family: Arial; }
        .product-block { border:1px solid #ccc; padding:15px; margin:10px; display:inline-block; width:150px; text-align:center; border-radius:5px; }
        .section { margin-bottom:20px; }
        input[type=number]{ width:50px; }
        table { border-collapse: collapse; width:50%; }
        th, td { border:1px solid #ccc; padding:5px; text-align:center; }
    </style>
</head>
<body>

<p>Logged in as: <?= htmlspecialchars($_SESSION['user']) ?> | <a href="logout.php">Logout</a></p>

<!-- ================= Product Blocks ================= -->
<div class="section">
<h3>Products</h3>
<?php foreach($products as $p): ?>
<div class="product-block">
    <strong><?= $p['name'] ?></strong><br>
    $<?= number_format($p['price'],2) ?><br>
    <form method="post">
        <input type="hidden" name="product_id" value="<?= $p['id'] ?>">
        <input type="number" name="quantity" value="1" min="1"><br>
        <button type="submit" name="add_to_cart">Add to Cart</button>
    </form>
</div>
<?php endforeach; ?>
</div>

<!-- ================= Add New Product Directly to Cart ================= -->
<div class="section">
<h3>Add New Product Directly to Cart</h3>
<form method="post">
    <input type="text" name="new_name" placeholder="Product Name" required>
    <input type="number" name="new_price" step="0.01" placeholder="Price" required>
    <input type="number" name="new_qty" value="1" min="1">
    <button type="submit" name="add_new_product">➕ Add Product</button>
</form>
</div>

<!-- ================= Cart / Receipt ================= -->
<?php if($cart): ?>
<div class="section">
<h2>Receipt</h2>
<table>
    <thead>
        <tr><th>#</th><th>Product</th><th>Qty</th><th>Total</th></tr>
    </thead>
    <tbody>
    <?php $grandTotal = 0; foreach($cart as $i=>$item): $grandTotal += $item['total']; ?>
        <tr>
            <td><?= $i+1 ?></td>
            <td><?= $item['name'] ?></td>
            <td><?= $item['qty'] ?></td>
            <td>$<?= number_format($item['total'],2) ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
    <tfoot>
        <tr><td colspan="3">Grand Total</td><td>$<?= number_format($grandTotal,2) ?></td></tr>
    </tfoot>
</table>

<button onclick="window.print()">🖨 Print Receipt</button>
<form method="post">
    <button type="submit" name="clear_cart">Clear Cart</button>
</form>
</div>
<?php endif; ?>

</body>
</html>
