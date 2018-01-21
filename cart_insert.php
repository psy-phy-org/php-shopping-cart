<?php session_start(); ?>
<?php include('template/header.html'); ?>

<?php include('menu.html'); ?>

<header>
  <h1>Cart__insert</h1>
</header>

<?php
$id = $_REQUEST['id'];
if (! isset($_SESSION['product'])) {
    $_SESSION['product'] = [];
}
$count = 0;
if (isset($_SESSION['product'][$id])) {
    $count = $_SESSION['product'][$id]['count'];
}
$_SESSION['id'] = $_REQUEST['id'];
$_SESSION['product'][$id] = [
    'name' => $_REQUEST['name'],
    'price' => $_REQUEST['price'],
    'count' => $count + $_REQUEST['count']
];
echo <<<EOD
<p>カートに商品を追加しました。</p>
<hr>
EOD;
?>

<?php require('cart.php'); ?>

<?php include('template/footer.html'); ?>
