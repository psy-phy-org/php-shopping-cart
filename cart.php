<?php
if (! empty($_SESSION['product'])) {
    echo <<<EOD
    <table>
    <th>商品番号</th><th>商品名</th>
    <th>価格</th><th>個数</th><th>小計</th>
EOD;
    $total = 0;
    foreach ($_SESSION['product'] as $id => $product) {
        //$id = $product['id'];
        $name = $product['name'];
        $price = $product['price'];
        $pricef = number_format($product['price']);
        $count = $product['count'];
        echo <<<EOD
        <tr>
        <td>$id</td>
        <td><a href="products_detail.php?id=$id"></a>$name</td>
        <td>$pricef</td>
        <td>$count</td>
EOD;
        $subtotal = $price * $count;
        $total += $subtotal;
        $subtotalf = number_format($subtotal);
        $totalf = number_format($total);
        echo <<<EOD
        <td>{$subtotalf}</td>
        <td><a href="cart_delete.php?id=$id">削除</a></td>
        </tr>
EOD;
    }
    echo <<<EOD
    <tr><td>合計</td><td></td><td></td><td></td><td>$totalf</td><td></td></tr>
    </table>
EOD;
} else {
    echo '<p>カートに商品がありません。</p>';
}
