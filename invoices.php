nano invoices.php
<?php
require 'db.php';


$stmt = $pdo->query("SELECT * FROM invoices ORDER BY created_at DESC");
$invoices = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>POS Invoices</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<h1>POS Invoices / Reports</h1>
<table border="1" cellpadding="5" cellspacing="0">
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
<a href="index.php">Back to POS</a>
</body>
</html>

<?php
require 'db.php';

$stmt = $pdo->query("SELECT * FROM invoices ORDER BY created_at DESC");
$invoices = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>POS Invoices</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<h1>POS Invoices / Reports</h1>
<table border="1" cellpadding="5" cellspacing="0">
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
<a href="index.php">Back to POS</a>
</body>
</html>

