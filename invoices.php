<style>
body {
    font-family: 'Courier New', monospace;
    background: #fff;
    margin: 0;
}

#receipt {
    width: 280px;
    margin: auto;
    padding: 10px;
    font-size: 13px;
}

.header { text-align: center; }

.store-name {
    font-size: 18px;
    font-weight: bold;
}

.small { font-size: 12px; }

hr {
    border: none;
    border-top: 1px dashed #000;
    margin: 6px 0;
}

table {
    width: 100%;
    font-size: 13px;
    border-collapse: collapse;
}

td { padding: 3px 0; }

.right { text-align: right; }

.total-box {
    font-size: 15px;
    font-weight: bold;
}

.footer {
    text-align: center;
    font-size: 12px;
    margin-top: 8px;
}

@media print {
    body { margin: 0; }
    #receipt { width: 100%; }
    button { display: none; }
}
</style>
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
