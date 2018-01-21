<?php session_start(); ?>

<?php require_once('common/database.php') ?>

<?php include('template/header.html') ?>
<?php include('menu.html') ?>

<?php
if (! isset($_SESSION['customer'])) {
    header('Location: customer_login.php');
    exit();
}

$customer_name = htmlspecialchars($_SESSION['customer']['name']);
$customer_email = htmlspecialchars($_SESSION['customer']['email']);
$customer_password = htmlspecialchars($_SESSION['customer']['password']);
$customer_password2 = htmlspecialchars($_SESSION['customer']['password2']);

$customer_password = hash('sha256', $customer_password);

if (! empty($_POST)) {
    try {
        $dbh = connect_database();

        $sql = <<<SQL
INSERT INTO customers (name, email, password) VALUES (?,?,?)
SQL;

        $sth = $dbh->prepare($sql);
        $sth->bindValue(1, $customer_name, PDO::PARAM_INT);
        $sth->bindValue(2, $customer_email, PDO::PARAM_INT);
        $sth->bindValue(3, $customer_password, PDO::PARAM_INT);
        $sth->execute();
    } catch (PDOException $e) {
        print "ERR! : {$e->getMessage()}";
    } finally {
        $dbh = null;
    }

    header('Location: customer_edit_do.php');
    exit();
}
?>

<header>
  <h1>Customer__edit--check</h1>
</header>

<main>
  <p>入力した内容を確認して、「登録する」ボタンを押してください。</p>
  <form action="" method="post" name="form" id="form">
    <fieldset>
    <input type="hidden" name="action" value="submit">
      <p>ログイン名：<?php echo $customer_name; ?></p>
      <p>メールアドレス：<?php echo $customer_email; ?></p>
      <p>パスワード：●●●●</p>
      <p>
        <a href="customer_add.php?action=rewrite"
          role="button">書き直す</a>
      </p>
      <p>
        <a href="javascript:document.form.submit()">登録する</a>
      </p>
    </fieldset>
  </form>
</main>

<?php require 'template/footer.html'; ?>
