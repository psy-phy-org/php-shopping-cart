<?php session_start(); ?>

<?php include('template/header.html') ?>
<?php include('menu.html') ?>

<?php require_once('common/database.php'); ?>

<?php
if ($_SESSION['customer']) {
    header('Location: customer_edit.php');
    exit();
}

// $name=$address=$login=$password='';
// if (isset($_SESSION['customer'])) {
//     $customer_name=$_SESSION['customer']['name'];
//     $customer_email=$_SESSION['customer']['email'];
//     $customer_password=$_SESSION['customer']['password'];
// }


if (! empty($_POST)) {
    // validation
    if ($_POST['name'] == '') {
        $error['name'] = 'blank';
    }
    if ($_POST['email'] == '') {
        $error['email'] = 'blank';
    }
    if (strlen($_POST['password']) < 4) {
        $error['password'] = 'length';
    }
    if ($_POST['password'] == '') {
        $error['password'] = 'blank';
    }
    if ($_POST['password2'] != $_POST['password']) {
        $error['password2'] = 'mismatch';
    }

    // duplicate check
    try {
        $dbh = connect_database();

        $sql = sprintf("SELECT COUNT(*) AS cnt FROM customers WHERE email='%s' ", $_POST['email']);

        $sth = $dbh->query($sql);

        while ($row = $sth->fetch()) {
            $cnt = $row['cnt'];
            if ($cnt > 0) {
                $error['email'] = 'duplicate';
            }
        }
    } catch (PDOException $e) {
        print "ERR! : {$e->getMessage()}";
    } finally {
        $dbh = null;
    }

    if (empty($error)) {
        $_SESSION['customer'] = $_POST;

        header('Location: customer_add_check.php');
        exit();
    }
}

// rewrite
if (isset($_REQUEST['action']) == 'rewrite') {
    $_POST = $_SESSION['customer'];
    $error['rewite'] = true;
}
?>

<?php require 'template/header.html'; ?>

<?php
$customer_name = htmlspecialchars($_POST['name']);
$customer_email = htmlspecialchars($_POST['email']);
$customer_pass = htmlspecialchars($_POST['password']);
$customer_pass2 = htmlspecialchars($_POST['password2']);
?>

<header>
  <h1>customer - add</h1>
</header>

<main>
  <p>情報を入力後、「入力内容確認」ボタンを押してください。</p>
  <form action="" method="post" name="form" id="form">
    <fieldset>
      <p>
        <input type="text" name="name" size="35" maxlength="255"
          placeholder="ログイン名" value="<?php echo $customer_name; ?>">
      </p>
<?php if (isset($error['name']) && $error['name'] =='blank'): ?>
      <p>ログイン名が入力されていません。</p>
<?php endif; ?>
      <p>
        <input type="text" name="email" size="35" maxlength="255"
          placeholder="メールアドレス" value="<?php echo $customer_email; ?>">
      </p>
<?php if (isset($error['email']) && $error['email'] =='blank'): ?>
      <p>メールアドレスが入力されていません。</p>
<?php elseif (isset($error['email']) && $error['email'] = 'duplicate'): ?>
      <p>指定されたメールアドレスはすでに登録されています。</p>
<?php endif; ?>
      <p>
        <input type="password" name="password" size="35" maxlength="255"
          placeholder="パスワード" value="<?php echo $customer_password; ?>">
      </p>
<?php if (isset($error['password']) && $error['password'] =='blank'): ?>
      <p>パスワードが入力されていません。</p>
<?php elseif (isset($error['password']) && $error['password']=='length'): ?>
      <p>パスワードは4文字以上で入力してください。</p>
<?php endif; ?>
      <p>
        <input type="password" name="password2" size="35" maxlength="255"
          placeholder="パスワード再入力" value="<?php echo $customer_password2; ?>">
      </p>
<?php if (isset($error['password2']) && $error['password2']=='mismatch'): ?>
      <p>パスワードが一致しません。</p>
<?php endif; ?>
      <p>
        <a href="javascript:document.form.submit()">入力内容確認</a>
      </p>
    </fieldset>
  </form>
</main>

<?php include('template/footer.html') ?>
