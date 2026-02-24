<?php
session_start();
require 'functions.php';

/* ================= LOGIN CHECK ================= */
if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

/* ================= INIT PRODUCTS ================= */
if (!isset($_SESSION['products'])) {
    $_SESSION['products'] = [
        ['id'=>1,'name'=>'Tea','price'=>2.50],
        ['id'=>2,'name'=>'Coffee','price'=>3.50],
        ['id'=>3,'name'=>'Coca Cola','price'=>0.50],
        ['id'=>4,'name'=>'Cake','price'=>10.50]
    ];
}

$products = &$_SESSION['products'];

if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

/* ================= POST LOGIC ================= */
if ($_SERVER['REQUEST_METHOD']=='POST') {

    // Add product to system
    if (isset($_POST['add_product_system'])) {
        $newId = max(array_column($products,'id')) + 1;
        $name = trim($_POST['system_name']);
        $price = (float)$_POST['system_price'];
        $products[] = ['id'=>$newId,'name'=>$name,'price'=>$price];
    }

    // Add to cart
    if (isset($_POST['product_id'], $_POST['quantity'])) {
        $id = (int)$_POST['product_id'];
        $qty = max(1,(int)$_POST['quantity']);

        $foundIndex = null;
        foreach($_SESSION['cart'] as $index => $item){
            if($item['id']==$id){
                $foundIndex = $index;
                break;
            }
        }

        if($foundIndex!==null){
            $_SESSION['cart'][$foundIndex]['qty'] += $qty;
            $_SESSION['cart'][$foundIndex]['total'] =
                $_SESSION['cart'][$foundIndex]['price'] *
                $_SESSION['cart'][$foundIndex]['qty'];
        } else {
            foreach($products as $p){
                if($p['id']==$id){
                    $_SESSION['cart'][] = [
                        'id'=>$p['id'],
                        'name'=>$p['name'],
                        'price'=>$p['price'],
                        'qty'=>$qty,
                        'total'=>$p['price']*$qty
                    ];
                    break;
                }
            }
        }
    }

    // Clear cart
    if(isset($_POST['clear_cart'])) $_SESSION['cart'] = [];
}

$cart = $_SESSION['cart'];
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>BUTHMAIYA POS System</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<p>
Logged in as: <?= htmlspecialchars($_SESSION['user']) ?> |
<a href="logout.php">Logout</a>
</p>

<!-- Add Product -->
<h3>Add Product to System</h3>
<form method="post">
<input type="text" name="system_name" placeholder="Product Name" required>
<input type="number" step="0.01" name="system_price" placeholder="Price" required>
<button type="submit" name="add_product_system">➕ Add</button>
</form>

<hr>

<!-- Product List -->
<h3>Products</h3>
<?php foreach($products as $p): ?>
<form method="post" style="margin-bottom:10px;">
<strong><?= htmlspecialchars($p['name']) ?></strong>
($<?= number_format($p['price'],2) ?>)
<input type="hidden" name="product_id" value="<?= $p['id'] ?>">
<input type="number" name="quantity" value="1" min="1" style="width:60px;">
<button type="submit">Add to Cart</button>
</form>
<?php endforeach; ?>

<hr>

<!-- Receipt -->
<?php if($cart): ?>
<h2>Receipt</h2>

<div id="receipt">

    <h2>BUTHMAIYA Mart Receipt</h2>
    <p style="text-align:center;font-size:13px;">
        Phnom Penh, Cambodia<br>
        Tel: 012 345 678<br>
        Date: <?= date("d-m-Y H:i") ?><br>
        Cashier: <?= htmlspecialchars($_SESSION['user']) ?>
    </p>
    <hr>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Item</th>
                <th>Qty</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
        <?php 
        $grandTotal=0;
        foreach($cart as $i=>$item):
        $grandTotal+=$item['total'];
        ?>
        <tr>
            <td><?= $i+1 ?></td>
            <td><?= htmlspecialchars($item['name']) ?></td>
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

    <hr>
    <p style="text-align:center;font-size:12px;">
        Thank you for shopping with us ❤️<br>
        Please come again!
    </p>

</div>

<br>
<button onclick="window.print()">🖨 Print Receipt</button>

<form method="post">
<button type="submit" name="clear_cart">Clear Cart</button>
</form>

<?php endif; ?>

</body>
</html>
