<?php
session_start();
require 'functions.php';

if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

$cart = $_SESSION['cart'] ?? [];
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>BUTHMAIYA Mart Receipt</title>

<style>
body{
    font-family: monospace;
}

#receipt{
    width: 280px;
    margin:auto;
    font-size:13px;
}

.center{text-align:center;}
.right{text-align:right;}

table{
    width:100%;
    font-size:12px;
}

hr{
    border:0;
    border-top:1px dashed #000;
}

@media print{
    body *{
        visibility:hidden;
    }
    #receipt, #receipt *{
        visibility:visible;
    }
    #receipt{
        position:absolute;
        left:0;
        top:0;
        width:280px;
    }
    button{
        display:none;
    }
}
</style>

<script>
window.onload = function(){
    window.print();   // auto print
}
</script>

</head>
<body>

<?php if($cart): ?>

<div id="receipt">

    <div class="center">
        <h3>BUTHMAIYA MART</h3>
        Phnom Penh, Cambodia<br>
        Tel: 012 345 678<br>
        -----------------------------
    </div>

    Date: <?= date("d-m-Y H:i") ?><br>
    Cashier: <?= htmlspecialchars($_SESSION['user']) ?><br>
    --------------------------------

    <table>
        <?php 
        $grandTotal=0;
        foreach($cart as $item):
            $grandTotal += $item['total'];
        ?>
        <tr>
            <td colspan="3"><?= htmlspecialchars($item['name']) ?></td>
        </tr>
        <tr>
            <td><?= $item['qty'] ?> x $<?= number_format($item['price'],2) ?></td>
            <td></td>
            <td class="right">
                $<?= number_format($item['total'],2) ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    --------------------------------
    <div class="right">
        <strong>TOTAL: $<?= number_format($grandTotal,2) ?></strong>
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
</div>

<?php else: ?>
<p>No items in cart.</p>
<?php endif; ?>

</body>
</html>
