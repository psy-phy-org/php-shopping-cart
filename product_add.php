<?php include('template/header.html'); ?>

<?php include('menu.html'); ?>

<header>
  <h1>Product__add</h1>
</header>

<main>
 <form method="post" action="product_add_do.php" name="form"
  id="form" enctype="multipart/form-data">
  <fieldset>
   <table>
    <tr>
     <th><label for="id">商品番号</label></th>
     <td><input type="text" name="id" id="id" size="40" disabled></td>
    </tr>
    <tr>
     <th><label for="name">商品名</label></th>
     <td><input type="text" name="name" id="name" size="40"></td>
    </tr>
    <tr>
     <th><label for="price">価格</label></th>
     <td><input type="text" name="price" id="price" size="40"></td>
    </tr>
    <tr>
     <th><label for="stock">数量</label></th>
     <td><input type="text" name="stock" id="stock" size="40"></td>
    </tr>
        <tr>
     <th><label for="image_path">画像</label></th>
     <td><input name="image_path" type="file" id="image_path" size="40"></td>
    </tr>
    <tr>
     <th><label for="created">登録日</label></th>
     <td><input type="text" name="created" id="created"
      placeholder="現在の日時が登録されます。" size="40" disabled></td>
    </tr>
   </table>
  </fieldset>
  <p>
   <a href="javascript:document.form.submit()">登録する</a>
  </p>
  <input type="hidden" name="form" value="">
 </form>
</main>

<?php include('template/footer.html'); ?>
