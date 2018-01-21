<?php session_start(); ?>
<?php include('template/header.html'); ?>

<?php include('menu.html'); ?>

<header>
  <h1>Purchase__input</h1>
</header>

<?php
$id = $_SESSION['id'];

if (! isset($_SESSION['customer'])) {
    echo '購入手続きを行うにはログインしてください。';
} elseif (empty($_SESSION['product'])) {
    echo 'カートに商品がありません。';
} else {
    echo '<p>お名前：', $_SESSION['customer']['name'], '</p>';
    echo '<p>メールアドレス：', $_SESSION['customer']['email'], '</p>';
    echo '<hr>';
    require('cart.php');
    echo '<hr>';
    echo '<p>内容をご確認いただき、購入を確定してください。</p>';
    echo '<a href="purchase_output.php">購入を確定する</a>';
}
?>

<?php include('template/footer.html'); ?>
