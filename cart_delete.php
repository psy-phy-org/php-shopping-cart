<?php session_start(); ?>

<?php include('template/header.html'); ?>

<?php include('menu.html'); ?>

<header>
  <h1>cart_delete</h1>
</header>

<?php
unset($_SESSION['product'][$_REQUEST['id']]);
echo <<<EOD
<p>カートから商品を削除しました。</p>
<hr>
EOD;
?>

<?php require('cart.php'); ?>

<?php include('template/footer.html'); ?>
