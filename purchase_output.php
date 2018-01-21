<?php session_start(); ?>
<?php include('template/header.html'); ?>

<?php include('menu.html'); ?>

<header>
  <h1>Purchase__output</h1>
</header>

<?php
$id = $_SESSION['id'];

require_once('common/database.php');

try {
    // データベースへの接続を確立
    $dbh = connect_database();

    $sql = <<<SQL
select max(id) from purchase
SQL;
    $purchase_id = 1;
    foreach ($dbh->query($sql) as $row) {
        $purchase_id = $row['max(id)'] + 1;
    }
    // 例外処理を有効化
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // トランザクションを開始
    $dbh->beginTransaction();

    try {
        $sth = $dbh->prepare('insert into purchase values(?,?)');
        $sth->execute([
            $purchase_id,
            $_SESSION['customer']['id']
        ]);
        foreach ($_SESSION['product'] as $product_id => $product) {
            $sth = $dbh->prepare('insert into purchase_detail values(?,?,?)');
            $sth->execute([
                $purchase_id,
                $product_id,
                $product['count']
            ]);
        }

        $stock = $_SESSION['stock'] - $_SESSION['product'][$id]['count'];
        $sth = $dbh->prepare('update products set stock=? WHERE id=?');
        $sth->execute([
            $stock,
            $id
        ]);

        // コミット
        $dbh->commit();
    } catch (PDOException $e) {

        // ロールバック
        $dbh->rollback();
        throw $e;
    }
} catch (PDOException $e) {
    print "ERR! : {$e->getMessage()}";
} finally {
    $dbh = null;
}
unset($_SESSION['product']);
echo '購入手続きが完了しました。ありがとうございます。';
?>

<?php include('template/footer.html'); ?>
