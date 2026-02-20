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
<link rel="stylesheet" href="style.css">
</head>
<body class="login-body">
<div class="login-box">
<h2>POS System Login</h2>
<?php if($error): ?><p class="error"><?= $error ?></p><?php endif; ?>
<form method="post">
<input type="text" name="username" placeholder="Username" required>
<input type="password" name="password" placeholder="Password" required>
<button type="submit" name="login">Login</button>
</form>
</div>
</body>
</html>
<?php exit; }

/* ================= INIT PRODUCTS & CART ================= */
// Load products from JSON file
$productsFile = 'products.json';
if(file_exists($productsFile)){
    $_SESSION['products'] = json_decode(file_get_contents($productsFile), true);
} else {
    $_SESSION['products'] = [
        ['id'=>1,'name'=>'Tea','price'=>2.50],
        ['id'=>2,'name'=>'Coffee','price'=>3.50],
        ['id'=>3,'name'=>'Coka','price'=>0.50]
    ];
    // Save initial products
    file_put_contents($productsFile, json_encode($_SESSION['products'], JSON_PRETTY_PRINT));
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
        // Save to JSON file
        file_put_contents($productsFile, json_encode($products, JSON_PRETTY_PRINT));
    }

    // Add product to cart
    if (isset($_POST['product_id'], $_POST['quantity'])) {
        $id = (int)$_POST['product_id'];
        $qty = max(1,(int)$_POST['quantity']);

        // Check duplicate in cart
        $foundIndex = null;
        foreach($_SESSION['cart'] as $index => $item){
            if($item['id']==$id){
                $foundIndex = $index;
                break;
            }
        }

        if($foundIndex !== null){
            // Update qty
            $_SESSION['cart'][$foundIndex]['qty'] += $qty;
            $_SESSION['cart'][$foundIndex]['total'] = $_SESSION['cart'][$foundIndex]['price'] * $_SESSION['cart'][$foundIndex]['qty'];
        } else {
            // Add new item
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
<title>POS System BUTHMAIYA</title>
<link rel="stylesheet" href="style.css">
<style>
.section{border:1px solid #ccc;padding:15px;margin-bottom:20px;border-radius:5px;}
input, select, button{margin:5px 0;}
.product-blocks{display:flex;flex-wrap:wrap;gap:15px;}
.product-block{border:1px solid #ccc;border-radius:5px;padding:10px;width:120px;text-align:center;box-shadow:2px 2px 5px rgba(0,0,0,0.1);}
.product-block input{width:50px;}
table{border-collapse:collapse;width:50%;margin-top:20px;}
th, td{border:1px solid #ccc;padding:5px;text-align:center;}
</style>
</head>
<body>

<p>Logged in as: <?= htmlspecialchars($_SESSION['user']) ?> | <a href="logout.php">Logout</a></p>

<!-- Add Product to System -->
<div class="section">
<h3>Add Product to System</h3>
<form method="post">
<input type="text" name="system_name" placeholder="Product Name" required>
<input type="number" step="0.01" name="system_price" placeholder="Price" required>
<button type="submit" name="add_product_system">➕ Add to System</button>
</form>
</div>

<!-- Product Blocks -->
<div class="section">
<h3>Products</h3>
<div class="product-blocks">
<?php foreach($products as $p): ?>
<div class="product-block">
<strong><?= htmlspecialchars($p['name']) ?></strong><br>
$<?= number_format($p['price'],2) ?><br>
<form method="post">
<input type="hidden" name="product_id" value="<?= $p['id'] ?>">
<input type="number" name="quantity" value="1" min="1"><br>
<button type="submit">Add to Cart</button>
</form>
</div>
<?php endforeach; ?>
</div>
</div>

<!-- Cart / Receipt -->
<?php if($cart): ?>
<h2>Receipt</h2>
<div id="receipt">
<h2>BUTHMAIYA Mart Receipt</h2>
<table>
<thead>
<tr><th>#</th><th>Product</th><th>Qty</th><th>Total</th></tr>
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
</div>

<button onclick="window.print()">🖨 Print Receipt</button>
<form method="post">
<button type="submit" name="clear_cart">Clear</button>
</form>
<?php endif; ?>

</body>
</html>
