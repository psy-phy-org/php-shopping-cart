<?php
if (! isset($_SESSION['customer'])) {
    header('Location: customer_login.php');
    exit();
}
?>

<?php include('template/header.html') ?>
<?php include('menu.html') ?>

<header>
  <h1>Customer__add--do</h1>
</header>

<main>
  <p>メンバー登録が完了いたしました。</p>
  <p><a href="customer_login.php">ログイン</a></p>
</main>

<?php include('template/footer.html') ?>
