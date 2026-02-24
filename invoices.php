<?php
session_start();
require 'functions.php';

if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

$cart = $_SESSION['cart'] ?? [];

if (empty($cart)) {
    header("Location: index.php");
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

<script>
window.onload = function(){
    window.print();
}
</script>

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

<?php foreach($cart as $item): ?>
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
<button onclick="window.print()">Print Again</button>
<a href="index.php">Back to POS</a>
</div>

</body>
</html>
