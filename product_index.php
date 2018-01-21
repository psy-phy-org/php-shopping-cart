<?php include('template/header.html'); ?>

<?php include('menu.html'); ?>

<header>
  <h1>Product__index</h1>
</header>

<main>
  <table>
    <tr>
      <th>画像</th>
      <th>商品番号</th>
      <th>商品名</th>
      <th>価格</th>
      <th>在庫</th>
    </tr>
<?php
require_once('common/database.php');

try {
    $dbh = connect_database();

    $sql = <<<SQL
SELECT * FROM products ORDER BY id DESC
SQL;

    $sth = $dbh->query($sql);
    if (empty($sth->fetch())) {
        echo <<<EOD
  <p style="margin-top: 50px; margin-bottom: 50px">There is no items in store at this time.</p>

EOD;
    } else {
        $sth = $dbh->query($sql);
        while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
            $id = $row['id'];
            $name = $row['name'];
            $price = number_format($row['price']);
            $stock = $row['stock'];
            $image_path = $row['image_path'];
            echo <<<EOD
    <tr>
      <td><img src="item_image/tmb_$image_path" alt=""></td>
      <td>$id</td>
      <td><a href="product_detail.php?id=$id">$name</a></td>
      <td>$price</td>
      <td>$stock</td>
    </tr>
EOD;
        }
    }
} catch (PDOException $e) {
    exit('ERR! : ' . $e->getMessage());
} finally {
    $pdo = null;
}
?>

  </table>
</main>

<?php include('template/footer.html'); ?>
