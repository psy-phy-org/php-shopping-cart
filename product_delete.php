<?php
require_once('common/database.php');

try {
    $dbh = connect_database();

    foreach ($dbh->query('SELECT * FROM products') as $row) {
        $image_path = $row['image_path'];
    }
    $folder = "item_image";

    // 既存画像の削除
    $deletefile=$image_path;

    // ファイルを開く
    $fp = fopen("$folder/"."$deletefile", "rb");
    // ファイルを閉じる
    fclose($fp);
    unlink("$folder/"."$deletefile");

    // 既存画像の削除
    $deletetmb='tmb_'.$image_path;

    // ファイルを開く
    $fp = fopen("$folder/"."$deletetmb", "rb");
    // ファイルを閉じる
    fclose($fp);
    unlink("$folder/"."$deletetmb");

    $sth = $dbh->prepare('DELETE FROM products WHERE id=?');
    $sth->bindValue(1, PDO::PARAM_INT);
    $sth->execute(array(
        $_GET['id']
    ));
} catch (PDOException $e) {
    print "ERR! : {$e->getMessage()}";
} finally {
    $pdo = null;
}

header('Location: product_admin.php');
