<?php session_start(); ?>
<?php include('template/header.html') ?>

<?php include('menu.html') ?>

<header>
  <h1>history</h1>
</header>

<?php
require_once('common/database.php');

if (isset($_SESSION['customer'])) {
    $sql_purchase = <<<SQL
select * from purchase where customer_id=? order by id desc
SQL;

    $sql_detail = <<<SQL
select * from purchase_detail,products where purchase_id=? and product_id=id
SQL;

    try {
        $dbh = connect_database();

        $sth_purchase = $dbh->prepare($sql_purchase);
        $sth_purchase->execute([
            $_SESSION['customer']['id']
        ]);

        foreach ($sth_purchase->fetchAll() as $row_purchase) {
            $sth_detail = $dbh->prepare($sql_detail);
            $sth_detail->execute([
                $row_purchase['id']
            ]);
            echo <<<EOD
              <table>
                <tr>
                  <th>商品番号</th><th>商品名</th><th>価格</th><th>個数</th><th>小計</th>
                </tr>
EOD;
            $total = 0;
            while ($row_detail = $sth_detail->fetch(PDO::FETCH_ASSOC)) {
                // foreach ($sth_detail->fetchAll() as $row_detail) {
                $id = $row_detail['id'];
                $name = $row_detail['name'];
                $price = $row_detail['price'];
                $pricef = number_format($row_detail['price']);
                $count = $row_detail['count'];
                echo <<<EOD
                <tr>
                <td>$id</td>
                <td><a href="product_detail.php?id=$id">$name</a></td>
                <td>$pricef</td>
                <td>$count</td>
EOD;
                $subtotal = $price * $count;
                $total += $subtotal;
                $subtotalf = number_format($subtotal);
                $totalf = number_format($total);
                echo <<<EOD
                <td>$subtotalf</td>
                </tr>
EOD;
            }
            echo <<<EOD
            <tr><td>合計</td><td></td><td></td><td></td><td>$totalf</td></tr>
            </table>
            <hr>
EOD;
        }
    } catch (PDOException $e) {
        exit('ERR! : ' . $e->getMessage());
    } finally {
        $pdo = null;
    }
} else {
    echo '購入履歴を表示するには、ログインしてください。';
}
?>

<?php include('template/footer.html') ?>
