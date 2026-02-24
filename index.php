<?php
session_start();
require 'functions.php';

if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

$cart = $_SESSION['cart'] ?? [];

if (!$cart) {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>BUTHMAIYA Mart Receipt</title>
<style>
body { font-family: monospace; }
#receipt { width:280px; margin:auto; }
.center { text-align:center; }
.right { text-align:right; }
hr { border-top:1px dashed #000; }

@media print {
    button { display:none; }
}
</style>
</head>
<body>

<div id="receipt">

<div class="center">
<h3>BUTHMAIYA MART</h3>
Phnom Penh, Cambodia<br>
Date: <?= date("d-m-Y H:i") ?><br>
Cashier: <?= htmlspecialchars($_SESSION['user']) ?>
</div>

<hr>

<?php 
$grand = 0;
foreach($cart as $item):
$grand += $item['total'];
?>

<?= htmlspecialchars($item['name']) ?><br>
<?= $item['qty'] ?> x $<?= number_format($item['price'],2) ?>
<span style="float:right">$<?= number_format($item['total'],2) ?></span>
<br style="clear:both"><br>

<?php endforeach; ?>

<hr>
<div class="right">
<strong>TOTAL: $<?= number_format($grand,2) ?></strong>
</div>

<hr>
<div class="center">
Thank You ❤️
</div>

</div>

<br>
<div class="center">
<button onclick="window.print()">Print</button>
</div>

</body>
</html>
