<?php
session_start();
require 'functions.php';

/* ================= LOGIN CHECK ================= */
if (!isLoggedIn()) {
    $error = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
        if (login(trim($_POST['username']), $_POST['password'])) {
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

/* ================= INIT PRODUCTS ================= */
if (!isset($_SESSION['products'])) {
    $_SESSION['products'] = [
        ['id'=>1,'name'=>'Tea','price'=>2.50],
        ['id'=>2,'name'=>'Coffee','price'=>3.50],
        ['id'=>3,'name'=>'Coke','price'=>0.50],
        ['id'=>4,'name'=>'Cake','price'=>10.50]
    ];
}

$products = &$_SESSION['products'];

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

/* ================= POST LOGIC ================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Add Product to System
    if (isset($_POST['add_product_system'])) {
        $name = trim($_POST['system_name']);
        $price = (float)$_POST['system_price'];

        if ($name !== '' && $price > 0) {
            $newId = !empty($products) ? max(array_column($products,'id')) + 1 : 1;
            $products[] = ['id'=>$newId,'name'=>$name,'price'=>$price];
        }

        header("Location: ".$_SERVER['PHP_SELF']);
        exit;
    }

    // Add Product to Cart
    if (isset($_POST['product_id'], $_POST['quantity'])) {

        $id = (int)$_POST['product_id'];
        $qty = max(1,(int)$_POST['quantity']);

        $foundIndex = null;

        foreach($_SESSION['cart'] as $index => $item){
            if($item['id'] == $id){
                $foundIndex = $index;
                break;
            }
        }

        if($foundIndex !== null){
            $_SESSION['cart'][$foundIndex]['qty'] += $qty;
            $_SESSION['cart'][$foundIndex]['total'] =
                $_SESSION['cart'][$foundIndex]['price'] *
                $_SESSION['cart'][$foundIndex]['qty'];
        } else {
            foreach($products as $p){
                if($p['id'] == $id){
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

        header("Location: ".$_SERVER['PHP_SELF']);
        exit;
    }

    // Clear Cart
    if(isset($_POST['clear_cart'])){
        $_SESSION['cart'] = [];
        header("Location: ".$_SERVER['PHP_SELF']);
        exit;
    }
}

$cart = $_SESSION['cart'];
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>POS System</title>
<style>
body{font-family:Arial;margin:20px;}
.section{border:1px solid #ccc;padding:15px;margin-bottom:20px;border-radius:5px;}
.product-blocks{display:flex;flex-wrap:wrap;gap:15px;}
.product-block{border:1px solid #ccc;padding:10px;width:120px;text-align:center;}
table{border-collapse:collapse;width:50%;margin-top:20px;}
th,td{border:1px solid #ccc;padding:5px;text-align:center;}
</style>
</head>
<body>

<p>Logged in as: <?= htmlspecialchars($_SESSION['user']) ?> |
<a href="logout.php">Logout</a></p>

<div class="section">
<h3>Add Product</h3>
<form method="post">
<input type="text" name="system_name" placeholder="Product Name" required>
<input type="number" step="0.01" name="system_price" placeholder="Price" required>
<button type="submit" name="add_product_system">Add</button>
</form>
</div>

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

<?php if($cart): ?>
<h2>Receipt</h2>
<table>
<tr><th>#</th><th>Product</th><th>Qty</th><th>Total</th></tr>
<?php 
$grandTotal=0;
foreach($cart as $i=>$item):
$grandTotal += $item['total'];
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
<td><strong>$<?= number_format($grandTotal,2) ?></strong></td>
</tr>
</table>

<button onclick="window.print()">Print Receipt</button>

<form method="post">
<button type="submit" name="clear_cart">Clear</button>
</form>
<?php endif; ?>

</body>
</html>
