
<?php
// POS system placeholder
$products = [
    ['id'=>1, 'name'=>'Coffee', 'price'=>3.5],
    ['id'=>2, 'name'=>'Tea', 'price'=>2.5],
    ['id'=>3, 'name'=>'Cake', 'price'=>4.0],
     ['id'=>4, 'name'=>'Cocoun', 'price'=>1.0],
    ['id'=>4, 'name'=>'Coka', 'price'=>0.50],
];

$total = 0;
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['product_id'])) {
    $id = (int)$_POST['product_id'];
    foreach ($products as $p) {
        if ($p['id'] == $id) {
            $total = $p['price'];
            $selected = $p['name'];
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Simple POS</title>
</head>
<body>
<h1>POS Mr.RISA</h1>
<form method="post">
    <label>Select Product:</label>
    <select name="product_id">
        <?php foreach($products as $p): ?>
        <option value="<?= $p['id'] ?>"><?= $p['name'] ?> ($<?= $p['price'] ?>)</option>
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
