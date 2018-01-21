<?php
if (isset($_SESSION['customer'])) {
    echo '<table>';
    echo '<th>商品番号</th><th>商品名</th><th>価格</th>';

    require_once('common/database.php');

    try {
        $dbh = connect_database();

        $sql = <<<SQL
select * from favorite, products where customer_id=? and product_id=id
SQL;
        $sth = $dbh->prepare($sql);
        $sth->execute([
            $_SESSION['customer']['id']
        ]);
            while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
            $id = $row['id'];
            $name = $row['name'];
            $price = $row['price'];
            $pricef = number_format($row['price']);
            echo <<<EOD
            <tr>
            <td>$id</td>
            <td><a href="product_detail.php?id=$id">$name</a></td>
            <td>$pricef</td>
            <td><a href="favorite_delete.php?id=$id">削除</a></td>
            </tr>
EOD;
        }
        echo '</table>';
    } catch (PDOException $e) {
        exit('ERR! : ' . $e->getMessage());
    } finally {
        $pdo = null;
    }
} else {
    echo 'お気に入りを表示するには、ログインしてください。';
}
?>
