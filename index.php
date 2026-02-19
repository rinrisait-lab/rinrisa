<?php
session_start();

// Product list
$products = [
    ['id'=>1, 'name'=>'Tea', 'price'=>2.50],
    ['id'=>2, 'name'=>'Coffee', 'price'=>3.50],
    ['id'=>3, 'name'=>'Coka', 'price'=>0.50],
    ['id'=>4, 'name'=>'Cocoun', 'price'=>1.50],
];

// Initialize cart in session
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Add product to cart
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['product_id'], $_POST['quantity'])) {
    $id = (int)$_POST['product_id'];
    $qty = max(1, (int)$_POST['quantity']);

    foreach ($products as $p) {
        if ($p['id'] == $id) {
            // Check if product already in cart
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

// Clear cart
if (isset($_POST['clear_cart'])) {
    $_SESSION['cart'] = [];
}

// Assign cart
$cart = $_SESSION['cart'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>POS System</title>
</head>
<body>
<h1>POS System</h1>

<!-- Add product form -->
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
// force update
