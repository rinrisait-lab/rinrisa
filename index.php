<?php
session_start();
require 'functions.php';

// =====================
// LOGIN CHECK
// =====================
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
    <h2>Login POS System</h2>
    <?php if($error) echo "<p style='color:red;'>$error</p>"; ?>
    <form method="post">
        Username: <input type="text" name="username" required><br>
        Password: <input type="password" name="password" required><br>
        <button type="submit" name="login">Login</button>
    </form>
    <?php
    exit; // stop execution until logged in
}

// =====================
// LOGGED-IN USER
// =====================
echo '<p>Logged in as: ' . htmlspecialchars($_SESSION['user']) . ' | <a href="logout.php">Logout</a></p>';

// =====================
// PRODUCTS
// =====================
$products = [
    ['id'=>1, 'name'=>'Tea', 'price'=>2.50],
    ['id'=>2, 'name'=>'Coffee', 'price'=>3.50],
    ['id'=>3, 'name'=>'Coka', 'price'=>0.50],
    ['id'=>4, 'name'=>'Cocoun', 'price'=>1.50],
    ['id'=>5, 'name'=>'Cake', 'price'=>6.50],
];

// =====================
// INITIALIZE CART
// =====================
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// =====================
// HANDLE ADD TO CART
// =====================
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (isset($_POST['product_id'], $_POST['quantity'])) {
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
                        'id' => $p['id'],
                        'name' => $p['name'],
                        'price' => $p['price'],
                        'qty' => $qty,
                        'total' => $p['price'] * $qty
                    ];
                }
            }
        }
    }

    // CLEAR CART
    if (isset($_POST['clear_cart'])) {
        $_SESSION['cart'] = [];
    }
}

$cart = $_SESSION['cart'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>POS System</title>
</head>
<body>
<h1>POS System BUTHMAIYA</h1>

<!-- ADD PRODUCT FORM -->
<form method="post">
    <label>Select Product:</label>
    <select name="product_id">
        <?php foreach($products as $p): ?>
        <option value="<?= $p['id'] ?>"><?= $p['name'] ?> ($<?= number_format($p['price'],2) ?>)</option>
        <?php endforeach; ?>
    </select>
    <label>Quantity:</label>
    <input type="number" name="quantity" value="1" min="1">
    <button type="submit">Add to Cart</button>
</form>

<!-- CART TABLE -->
<?php if($cart): ?>
<h2>Receipt</h2>
<form method="post">
    <table border="1" cellpadding="5">
        <tr>
            <th>#</th>
            <th>Product</th>
            <th>Price</th>
            <th>Qty</th>
            <th>Total</th>
        </tr>
        <?php 
        $grandTotal = 0;
        foreach($cart as $i => $item):
            $grandTotal += $item['total'];
        ?>
        <tr>
            <td><?= $i+1 ?></td>
            <td><?= $item['name'] ?></td>
            <td>$<?= number_format($item['price'],2) ?></td>
            <td><?= $item['qty'] ?></td>
            <td>$<?= number_format($item['total'],2) ?></td>
        </tr>
        <?php endforeach; ?>
        <tr>
            <td colspan="4"><strong>Grand Total</strong></td>
            <td><strong>$<?= number_format($grandTotal,2) ?></strong></td>
        </tr>
    </table>
    <br>
    <button type="submit" name="clear_cart">Clear Cart</button>
</form>
<?php endif; ?>

</body>
</html>
