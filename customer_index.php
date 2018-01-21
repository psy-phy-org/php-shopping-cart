<?php session_start(); ?>

<?php
if (! isset($_SESSION['customer'])) {
    header('Location: customer_login.php');
    exit();
}
?>

<?php include('template/header.html') ?>
<?php include('menu.html') ?>

<header>
  <h1>Customer__index</h1>
</header>

<main>
<?php $test = $_SESSION['customer']['email']; ?>
  <h2>Hello! <?php echo $test; ?></h2>
  <p>
    <a href="member_logout.php">ログアウト</a> <input type="hidden"
      name="form" value="">
  </p>
</main>

<?php include('template/footer.html') ?>
