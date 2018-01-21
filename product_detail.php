<?php session_start(); ?>
<?php require('template/header.html'); ?>

<?php require('menu.html'); ?>

<header>
  <h1>Products__detail</h1>
</header>

<?php
require_once('common/database.php');

try {
    $dbh = connect_database();

    $sql = <<<SQL
select * from products where id=?
SQL;

    $sth = $dbh->prepare($sql);

    $sth->execute([
        $_REQUEST['id']
    ]);

    while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
        $id = $row['id'];
        $name = $row['name'];
        $price = $row['price'];
        $pricef = number_format($row['price']);
        $stock = $row['stock'];
        $image_path = $row['image_path'];
        echo <<<EOD
        <p><img src="item_image/$image_path" alt="" /></p>
        <form action="cart_insert.php" method="post">
        <p>商品番号：$id</p>
        <p>商品名：$name</p>
        <p>価格：$pricef</p>
        <p>個数：<select name="count">
EOD;
        for ($i = 1; $i <= $stock; $i ++) {
            echo '<option value="', $i, '">', $i, '</option>';
        }
        echo <<<EOD
        </select></p>
        <input type="hidden" name="id" value="$id">
        <input type="hidden" name="name" value="$name">
        <input type="hidden" name="price" value="$price">
        <p><input type="submit" value="カートに追加"></p>
        </form>
        <p><a href="favorite_insert.php?id=$id">お気に入りに追加</a></p>
EOD;
        $_SESSION['stock'] = $stock;
    }
} catch (PDOException $e) {
    print "ERR! : {$e->getMessage()}";
} finally {
    $dbh = null;
}
?>

<?php require('template/footer.html'); ?>
