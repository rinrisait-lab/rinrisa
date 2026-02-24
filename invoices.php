<?php
session_start();
require 'functions.php';

/* ================= LOGIN CHECK ================= */
if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

/* ================= GET CART ================= */
$cart = $_SESSION['cart'] ?? [];

/* ================= IF EMPTY CART ================= */
if (empty($cart)) {
    header("Location: index.php");
    exit;
}

/* ================= CALCULATE TOTAL ================= */
$grand = 0;
foreach ($cart as $item) {
    $grand += $item['total'];
}

/* OPTIONAL: Clear cart after loading receipt */
// $_SESSION['cart'] = [];
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>BUTHMAIYA Mart Receipt</title>

<style>
body {
    font-family: monospace;
    background: #fff;
}

#receipt {
    width: 280px;
    margin: auto;
    font-size: 13px;
}

.center { text-align: center; }
.right  { text-align: right; }

hr {
    border: 0;
    border-top: 1px dashed #000;
}

@media print {
    button { display: none; }
}
</style>

<script>
window.onload = function() {
    window.print();
};
</script>

</head>
<body>

<div id="receipt">

<div class="center">
    <h3>BUTHMAIYA MART</h3>
    Phnom Penh, Cambodia<br>
    Tel: 012 345 678<br>
    ----------------------------
</div>

Date: <?= date("d-m-Y H:i") ?><br>
Cashier: <?= htmlspecialchars($_SESSION['user']) ?><br>
--------------------------------

<br>

<?php foreach ($cart as $item): ?>
<?= htmlspecialchars($item['name']) ?><br>
<?= $item['qty'] ?> x $<?= number_format($item['price'],2) ?>
<span style="float:right">$<?= number_format($item['total'],2) ?></span>
<br style="clear:both"><br>
<?php endforeach; ?>

--------------------------------
<div class="right">
<strong>TOTAL: $<?= number_format($grand,2) ?></strong>
</div>
--------------------------------

<div class="center">
Thank You ❤️<br>
Please Come Again
</div>

</div>

<br>
<div class="center">
<button onclick="window.print()">Print Again</button>
<a href="index.php">Back to POS</a>
</div>

</body>
</html>
