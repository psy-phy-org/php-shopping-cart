<?php
session_start();

$_SESSION = array();
if (isset($_COOKIE[session_name()]) == true) {
    setcookie(session_name(), '', time() - 1800, '/');
}
session_destroy();

header('Location:customer_login.php');
exit();
?>

<?php include('template/header.html') ?>

<?php include('menu.html') ?>

<header>
  <h1>Customer__logout</h1>
</header>

<main>
  <p>ログアウトしました。</p>
  <p><a href="members_login.php">ログインする</a></p>
</main>

<?php include('template/footer.html') ?>
