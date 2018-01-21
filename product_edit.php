<?php include('template/header.html'); ?>

<?php include('menu.html'); ?>

<?php
require_once('common/database.php');

$id = htmlspecialchars($_GET['id']);

$sql = <<<SQL
SELECT * FROM products WHERE id=?
SQL;

try {
    $dbh = connect_database();

    $sth = $dbh->prepare($sql);
    $sth->execute(array(
        $id
    ));

    $row = $sth->fetch();
    $id = htmlspecialchars($row['id']);
    $name = htmlspecialchars($row['name']);
    $price = htmlspecialchars($row['price']);
    $stock = htmlspecialchars($row['stock']);
    $image_path = htmlspecialchars($row['image_path']);
} catch (PDOException $e) {
    print "ERR! : {$e->getMessage()}";
} finally {
    $dbh = null;
}
?>

<?php require('template/header.html'); ?>

<header>
 <h1>Products__admin--edit</h1>
</header>

<main>
 <form action="product_edit_do.php" method="post" name="form"
  id="form"  enctype="multipart/form-data">
  <fieldset>
   <table>
    <tr>
     <th>商品番号</th>
     <td><input type="text" name="id" value="<?php echo $id ?>"
      readonly="readonly"></td>
    </tr>
    <tr>
     <th>商品名</th>
     <td><input type="text" name="name" value="<?php echo $name ?>"></td>
    </tr>
    <tr>
     <th>価格</th>
     <td><input type="text" name="price" value="<?php echo $price ?>"></td>
    </tr>
    <tr>
     <th>数量</th>
     <td><input type="text" name="stock" value="<?php echo $stock ?>"></td>
    </tr>
    <tr>
     <th><label for="image_path">元の画像</label></th>
     <td><img src="item_image/<?php echo $image_path; ?>" alt="" /></td>
    </tr>
    <tr>
      <th><label for="image_path">新しい画像</label></th>
      <td>
        <input name="image_path" type="file" id="image_path">
        <input name="image_path_exist" type="hidden" id="image_path_exist" size="50" value="<?php echo $image_path; ?>" />
      </td>
    </tr>
   </table>
   <div>
    <p>
      <a href="javascript:document.form.submit()">更新する</a>
    </p>
    <input type="hidden" name="form" value="" />
   </div>
  </fieldset>
 </form>
</main>

<?php include('template/footer.html'); ?>
