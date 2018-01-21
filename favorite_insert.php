<?php session_start(); ?>

<?php include('template/header.html') ?>
<?php include('menu.html') ?>

<header>
  <h1>favorite_insert</h1>
</header>

<?php
require_once('common/database.php');

if (isset($_SESSION['customer'])) {
    $sql = <<<SQL
insert into favorite values(?,?)
SQL;

    try {
        $dbh = connect_database();

        $sth = $dbh->prepare($sql);
        $sth->execute([
            $_SESSION['customer']['id'],
            $_REQUEST['id']
        ]);
        echo 'お気に入りに商品を追加しました。';
        echo '<hr>';
        require 'favorite.php';
    } catch (PDOException $e) {
        exit('ERR! : ' . $e->getMessage());
    } finally {
        $pdo = null;
    }
} else {
    echo 'お気に入りに商品を追加するには、ログインしてください。';
}
?>
<?php include('template/footer.html') ?>
