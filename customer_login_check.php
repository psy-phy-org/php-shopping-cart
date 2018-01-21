<?php session_start(); ?>

<?php require_once('common/database.php') ?>

<?php include('template/header.html') ?>

<?php include('menu.php') ?>

<?php
if (! isset($_SESSION['customer'])) {
    header('Location: customer_login.php');
    exit();
}

$customer_email = htmlspecialchars($_SESSION['customer']['email']);
$customer_password = htmlspecialchars($_SESSION['customer']['password']);

$customer_password = hash('sha256', $customer_password);

try {
    $dbh = connect_database();

    $sql = <<<SQL
SELECT email, password FROM customers WHERE email=? AND password=?
SQL;

    $sth = $dbh->prepare($sql);
    $sth->bindValue(1, $customer_email, PDO::PARAM_INT);
    $sth->bindValue(2, $customer_password, PDO::PARAM_INT);
    $sth->execute();

    $rec = $sth->fetch(PDO::FETCH_ASSOC);

    if ($rec == false) {
        $error['password'] = 'wrong';
    } else {
        $sth = $dbh->prepare('SELECT * FROM customers WHERE email=?');
        $sth->bindValue(1, $customer_email, PDO::PARAM_INT);
        $sth->execute();
        while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
            $_SESSION['customer']['id'] = $row['id'];
            $_SESSION['customer']['name'] = $row['name'];
        }
        header('Location:product_index.php');
        exit();
    }
} catch (PDOException $e) {
    print "ERR! : {$e->getMessage()}";
} finally {
    $dbh = null;
}
?>

<?php require 'template/header.html'; ?>

<header>
  <h1>Customer__login--check</h1>
</header>

<main>
<?php if (isset($error['password']) && $error['password'] =='wrong'): ?>
    メールアドレスかパスワードが間違っています。<p></p>
<?php endif; ?>
  <p><a href="customer_login.php">戻る</a></p>
</main>

<?php include('template/footer.html') ?>
