<?php
session_start();
require 'functions.php';

// LOGIN CHECK
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
    exit;
}

echo '<p>Logged in as: ' . htmlspecialchars($_SESSION['user']) . ' | <a href="logout.php">Logout</a></p>';

$products = [
    ['id'=>1, 'name'=>'Tea', 'price'=>2.50],
    ['id'=>2, 'name'=>'Coffee', 'price'=>3.50],
    ['id'=>3, 'name'=>'Coka', 'price'=>0.50],
    ['id'=>4, 'name'=>'Cocoun', 'price'=>1.50],
    ['id'=>5, 'name'=>'Cake', 'price'=>6.50],
];

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

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
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h1>POS System BUTHMAIYA</h1>

<form method="post">
    <label>Select Product:</label>
    <select name="product_id">
        <?php foreach($products as $p): ?>
        <option value="<?= $p['id'] ?>"><?= $p['name'] ?> ($<?= number_format($p['price'],2) ?>)</option>
        <?php endforeach; ?>
    </select>
    <label>Quantity:</label>
    <input type="number" name="quantity" value="1" min="1">
    <button type="submit">OK</button>
</form>

<?php if($cart): ?>
<h2>Receipt</h2>
<div id="receipt">
    <h2> BUTHMAIYA Mart  Receipt</h2>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Product</th>
                <th>Qty</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $grandTotal = 0;
            foreach($cart as $i => $item):
                $grandTotal += $item['total'];
            ?>
            <tr>
                <td><?= $i+1 ?></td>
                <td><?= $item['name'] ?></td>
                <td><?= $item['qty'] ?></td>
                <td>$<?= number_format($item['total'],2) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3">Grand Total</td>
                <td>$<?= number_format($grandTotal,2) ?></td>
            </tr>
        </tfoot>
    </table>
</div>

<button onclick="window.print()">🖨 Print Receipt</button>

<form method="post">
    <button type="submit" name="clear_cart">Clear</button>
</form>
<?php endif; ?>

</body>
</html>

