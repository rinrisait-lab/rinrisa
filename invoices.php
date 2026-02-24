<?php
session_start();
require 'functions.php';

if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

$cart = $_SESSION['cart'] ?? [];

if (empty($cart)) {
    echo "<h3 style='text-align:center;margin-top:50px;'>Cart is empty!</h3>";
    exit;
}

$grand = 0;
foreach ($cart as $item) {
    $grand += $item['total'];
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Receipt</title>

<style>
body {
    font-family: monospace;
    background: #f5f5f5;
}

#receipt {
    width: 320px;
    margin: 30px auto;
    background: #fff;
    padding: 20px;
    box-shadow: 0 0 10px rgba(0,0,0,0.2);
}

.header {
    text-align: center;
}

.store-name {
    font-size: 20px;
    font-weight: bold;
}

.small {
    font-size: 13px;
}

hr {
    border: none;
    border-top: 1px dashed #000;
    margin: 10px 0;
}

table {
    width: 100%;
    font-size: 14px;
}

td {
    padding: 4px 0;
}

.right {
    text-align: right;
}

.total-box {
    font-size: 16px;
    font-weight: bold;
}

.footer {
    text-align: center;
    margin-top: 10px;
    font-size: 13px;
}

button {
    padding: 6px 12px;
    margin-top: 10px;
    cursor: pointer;
}

@media print {
    body {
        background: #fff;
    }
    #receipt {
        box-shadow: none;
        margin: 0;
        width: 100%;
    }
    button {
        display: none;
    }
}
</style>

<script>
window.onload = function(){
    window.print();
};
</script>

</head>
<body>

<div id="receipt">

<div class="header">
    <div class="store-name">BUTHMAIYA MART</div>
    <div class="small">
        Phnom Penh, Cambodia<br>
        Date: <?= date("d-m-Y H:i") ?><br>
        Cashier: <?= htmlspecialchars($_SESSION['user']) ?>
    </div>
</div>

<hr>

<table>
<?php foreach($cart as $item): ?>
<tr>
    <td colspan="2"><strong><?= htmlspecialchars($item['name']) ?></strong></td>
</tr>
<tr>
    <td><?= $item['qty'] ?> x $<?= number_format($item['price'],2) ?></td>
    <td class="right">$<?= number_format($item['total'],2) ?></td>
</tr>
<?php endforeach; ?>
</table>

<hr>

<div class="total-box right">
TOTAL: $<?= number_format($grand,2) ?>
</div>

<hr>

<div class="footer">
Thank You ❤️<br>
Please Come Again
</div>

</div>

<div style="text-align:center;">
<button onclick="window.print()">Print Again</button>
</div>

</body>
</html>
