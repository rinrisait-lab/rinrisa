nano functions.php 

nano index.php     
nano invoices.php  
nano style.css     
<?php
$host = "127.0.0.1";
$db   = "pos_system";
$user = "NSM";
$pass = "NSM@admin"; 

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("DB Error: " . $e->getMessage());
}
?>
<?php
require 'db.php';

function getProducts() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM products");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function purchaseProduct($product_id, $qty) {
    global $pdo;
    // ... logic to insert into purchases / stock out
}

function getInvoices() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM invoices");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
<?php
require 'functions.php';
$products = getProducts();
$total = 0;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $id = (int)$_POST['product_id'];
    foreach ($products as $p) {
        if ($p['id'] == $id) {
            $total = $p['sell_price'];
            $selected = $p['name'];
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>POS System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<h1>POS System</h1>
<form method="post">
    <label>Select Product:</label>
    <select name="product_id">
        <?php foreach($products as $p): ?>
            <option value="<?= $p['id'] ?>"><?= $p['name'] ?> ($<?= $p['sell_price'] ?>)</option>
        <?php endforeach; ?>
    </select>
    <button type="submit">Calculate</button>
</form>

<?php if (isset($selected)): ?>
<p>Selected Product: <strong><?= $selected ?></strong></p>
<p>Total Price: <strong>$<?= $total ?></strong></p>
<?php endif; ?>
</body>
</html>
<?php
require 'functions.php';
$invoices = getInvoices();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Invoices</title>
</head>
<body>
<h1>Invoices</h1>
<table border="1">
    <tr>
        <th>ID</th>
        <th>Total</th>
        <th>Created At</th>
    </tr>
    <?php foreach($invoices as $inv): ?>
    <tr>
        <td><?= $inv['id'] ?></td>
        <td>$<?= $inv['total'] ?></td>
        <td><?= $inv['created_at'] ?></td>
    </tr>
    <?php endforeach; ?>
</table>
</body>
</html>
body { font-family: Arial, sans-serif; margin: 20px; }
h1 { color: #333; }
label { font-weight: bold; }
button { margin-top: 10px; }

