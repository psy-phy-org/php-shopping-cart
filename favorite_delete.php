<?php session_start(); ?>

<?php include('template/header.html') ?>
<?php include('menu.html') ?>

<header>
  <h1>favorite_delete</h1>
</header>

<?php
require_once('common/database.php');

if (isset($_SESSION['customer'])) {
    $sql = <<<SQL
delete from favorite where customer_id=? and product_id=?
SQL;

    try {
        $dbh = connect_database();

        $sth = $dbh->prepare($sql);
        $sth->execute([
            $_SESSION['customer']['id'],
            $_REQUEST['id']
        ]);

        echo 'お気に入りから商品を削除しました。';
        echo '<hr>';
    } catch (PDOException $e) {
        exit('ERR! : ' . $e->getMessage());
    } finally {
        $pdo = null;
    }
} else {
    echo 'お気に入りから商品を削除するには、ログインしてください。';
}
require('favorite.php');
?>

<?php include('template/footer.html') ?>
