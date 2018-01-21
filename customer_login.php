<?php session_start(); ?>
<?php include('template/header.html') ?>

<?php include('menu.html') ?>

<?php
if (! empty($_POST)) {
    if ($_POST['email'] == '') {
        $error['email'] = 'blank';
    }
    if ($_POST['password'] == '') {
        $error['password'] = 'blank';
    }

    $customer_email = htmlspecialchars($_POST['email']);
    $customer_pass = htmlspecialchars($_POST['password']);

    if (empty($error)) {
        $_SESSION['customer'] = $_POST;
        header('Location: customer_login_check.php');
        exit();
    }
}

$_SESSION = array();
if (isset($_COOKIE["PHPSESSID"])) {
    setcookie("PHPSESSID", '', time() - 1800, '/');
}
session_destroy();
?>

<?php include('template/header.html') ?>

<header>
  <h1>members - login</h1>
</header>

<main>
  <p>メールアドレスとパスワードを入力してください。</p>
  <form method="post" action="" name="form" id="form">
    <fieldset>
      <p>
        <input type="text" name="email" size="35" maxlength="255"
          placeholder="メールアドレス" value="<?php echo $customer_email; ?>">
<?php if (isset($error['email']) && $error['email'] =='blank'): ?>
      </p>
      <p>メールアドレスが入力されていません。</p>
<?php endif; ?>
      <p>
        <input type="password" name="password" placeholder="パスワード"
          value="">
<?php if (isset($error['password']) && $error['password'] =='blank'): ?>
      </p>
      <p>パスワードが入力されていません。</p>
<?php endif; ?>
      <p>
        <a href="javascript:document.form.submit()">ログイン</a>
      </p>
    </fieldset>
  </form>
  <p>
    <a href="customer_add.php">新規メンバー登録</a> <input type="hidden"
      name="form" value="">
  </p>
</main>

<?php include('template/footer.html') ?>
