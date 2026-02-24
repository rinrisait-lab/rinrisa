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

/* Calculate total */
$grand = 0;
foreach ($cart as $item) {
    $grand += $item['total'];
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>BUTHMAIYA MART Receipt</title>

<style>
body {
    font-family: 'Courier New', monospace;
    background: #f5f5f5;
}

/* ===== Screen View ===== */
#receipt {
    width: 320px;
    margin: 30px auto;
    background: #fff;
    padding: 20px;
    box-shadow: 0 0 10px rgba(0,0,0,0.2);
    font-size: 14px;
}

.header {
    text-align: center;
}

.store-name {
    font-size: 18px;
    font-weight: bold;
}

.small {
    font-size: 12px;
}

hr {
    border: none;
    border-top: 1px dashed #000;
    margin: 8px 0;
}

table {
    width: 100%;
    border-collapse: collapse;
}

td {
    padding: 4px 0;
    vertical-align: top;
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
    font-size: 13px;
    margin-top: 10px;
}

/* ===== PRINT FIX ===== */
@page {
    margin: 0;
}

@media print {

    body {
        background: #fff;
        margin: 0;
    }

    #receipt {
        width: 80mm;   /* Thermal width */
        margin: 0 auto;
        box-shadow: none;
    }

    table {
        width: 100%;
    }

    td {
        word-break: break-word;
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
    <td style="width:70%;">
        <strong><?= htmlspecialchars($item['name']) ?></strong><br>
        <?= $item['qty'] ?> x $<?= number_format($item['price'],2) ?>
    </td>
    <td class="right" style="width:30%;">
        $<?= number_format($item['total'],2) ?>
    </td>
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
