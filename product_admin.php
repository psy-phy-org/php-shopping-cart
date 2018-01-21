<?php include('template/header.html'); ?>

<?php include('menu.html'); ?>

<header>
  <h1>Products__admin</h1>
</header>

<div style="margin-top: 50px; margin-bottom: 150px">
  <table>
    <tr>
      <th>画像</th>
      <th>商品番号</th>
      <th>商品名</th>
      <th>価格</th>
      <th>在庫</th>
      <th>登録日</th>
      <th>更新日</th>
      <th>編集</th>
    </tr>

<?php
require_once('common/database.php');

try {
    $dbh = connect_database();

    $sql = <<<SQL
SELECT * FROM products ORDER BY id DESC
SQL;

    $sth = $dbh->query($sql);

    while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
        $id = $row['id'];
        $name = $row['name'];
        $price = number_format($row['price']);
        $stock = $row['stock'];
        $image_path = $row['image_path'];
        $created = $row['created'];
        $modified = $row['modified'];
        echo <<<EOD
    <tr>
      <td><img src="item_image/tmb_$image_path" alt=""></td>
      <td>$id</td>
      <td>$name</td>
      <td>$price</td>
      <td>$stock</td>
      <td>$created</td>
      <td>$modified</td>
      <td><a href="product_edit.php?id=$id">修正</a></td>
    </tr>
EOD;
    }
} catch (PDOException $e) {
    exit('ERR! : ' . $e->getMessage());
} finally {
    $pdo = null;
}
?>
</table>
  <p>
    <a href="product_add.php">新規登録</a>
  </p>
</main>

<?php include('template/footer.html'); ?>
