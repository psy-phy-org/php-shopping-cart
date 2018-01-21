<?php
require_once('common/database.php');

$sql = <<<SQL
INSERT INTO products (name, price, stock, image_path, created) VALUES(:name, :price, :stock, :image_path, :created)
SQL;

date_default_timezone_set('Asia/Tokyo');
$created = date("Y-m-d H:i:s");

try {
    $dbh = connect_database();

    $sth = $dbh->prepare($sql);

    $sth->bindValue(':name', $_POST['name']);
    $sth->bindValue(':price', $_POST['price']);
    $sth->bindValue(':stock', $_POST['stock']);
    $sth->bindValue(':image_path', $_FILES['image_path']['name']);
    $sth->bindValue(':created', $created);

    $sth->execute();

    /* 画像をリサイズしてアップロードする*/
    function uploadImage($tmpName, $dir, $maxWidth, $maxHeight)
    {
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($tmpName);

        if ($mime == 'image/jpeg' || $mime == 'image/pjpeg') {
            $ext = '.jpg';
            $image1 = imagecreatefromjpeg($tmpName);
        } elseif ($mime == 'image/png' || $mime == 'image/x-png') {
            $ext = '.png';
            $image1 = imagecreatefrompng($tmpName);
        } elseif ($mime == 'image/gif') {
            $ext = '.gif';
            $image1 = imagecreatefromgif($tmpName);
        } else {
            return false;
        }

        list($width1, $height1) = getimagesize($tmpName);

        if ($width1 <= $maxWidth && $height1 <= $maxHeight) {
            $scale = 1.0;
        } else {
            $scale = min($maxWidth / $width1, $maxHeight / $height1);
        }

        $width2 = $width1 * $scale;
        $height2 = $height1 * $scale;

        $image2 = imagecreatetruecolor($width2, $height2);

        if ($ext == '.gif') {
            $transparent1 = imagecolortransparent($image1);
            if ($transparent1 >= 0) {
                $index = imagecolorsforindex($image1, $transparent1);
                $transparent2 = imagecolorallocate($image2, $index['red'], $index['green'], $index['blue']);
                imagefill($image2, 0, 0, $transparent2);
                imagecolortransparent($image2, $transparent2);
            }
        } elseif ($ext == '.png') {
            imagealphablending($image2, false);
            $transparent = imagecolorallocatealpha($image2, 0, 0, 0, 127);
            imagefill($image2, 0, 0, $transparent);
            imagesavealpha($image2, true);
        }

        imagecopyresampled($image2, $image1, 0, 0, 0, 0, $width2, $height2, $width1, $height1);

        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }

        $filename = $_FILES['image_path']['name'];
        $filename= preg_replace('/\.[^.]+$/', '', $filename);
        $filename = $filename.$ext;
        $saveTo = $dir . $filename;

        if ($ext == '.jpg') {
            $quality = 80;
            imagejpeg($image2, $saveTo, $quality);
        } elseif ($ext == '.png') {
            imagepng($image2, $saveTo);
        } elseif ($ext == '.gif') {
            imagegif($image2, $saveTo);
        }

        imagedestroy($image1);
        imagedestroy($image2);

        return $saveTo;
    }

    if ($_SERVER["REQUEST_METHOD"] === 'POST'
        && !empty($_FILES['image_path']['tmp_name'])) {
        $now = new DateTime();

        $maxWidth = 300;    // 最大幅
        $maxHeight = 300;   // 最大高さ

        // 一時ファイルの場所
        $tmpName = $_FILES['image_path']['tmp_name'];

        // 保存先のディレクトリ
        $dir = __DIR__ . '/item_image/' ;
        $path = uploadImage($tmpName, $dir, $maxWidth, $maxHeight);
    }
    /*リサイズここまで*/

    /*サムネールの生成*/
    // 保存している元ファイル名
    $tmp_name = basename($path);
    // 元ファイルの幅と高さを取得
    list($width, $height) = getimagesize($dir . $tmp_name);
    #print "<br>幅：${width}<br>";
    #print "高さ：${height}<br>";

    // 縮小するファイルの幅が100のときの高さを取得
    $newheight = abs(100 * $height / $width);
    #print "幅100のときの高さ：${newheight}";

    // 空の画像を作成
    $image_p = imagecreatetruecolor(100, $newheight);

    // 元の画像をコピー
    $image = imagecreatefromjpeg($dir . $tmp_name);

    // サイズ変更
    imagecopyresampled(
        $image_p,
        $image,
        0,
        0,
        0,
        0,
        100,
        $newheight,
        $width,
        $height
    );

    // 新しいファイルの拡張子
    $extension= ".jpg";

    // 元ファイルと同じディレクトリに保存
    imagejpeg($image_p, $dir . 'tmb_'."$tmp_name");

    // 新しいファイルの名前
    $newfilename = 'tmb_'.$tmp_name;

    #print "<br>縮小ファイルの名前${newfilename}";
    #print "<br><img src = '$path$newfilename'>";
    /*サムネールの生成ここまで*/

    /* 通常の画像アップロード
    $folder = "item_image";
    if (file_exists($folder) == FALSE) {
        mkdir($folder, 0755);
    }
    $file = $_FILES['image_path'];
    // ファイルアップロードの処理をする
    $ext = substr($file['name'], - 4);
    if ($ext == '.gif' || $ext == '.jpg' || $ext == '.png') {
        $filePath = './item_image/' . $file['name'];
        move_uploaded_file($file['tmp_name'], $filePath);
        print('<img src="' . $filePath . '">');
    } else {
        print('※拡張子が .gif, .jpg, .png のいずれかのファイルをアップロードしてください');
    }
    */
} catch (PDOException $e) {
    print "ERR! : {$e->getMessage()}";
} finally {
    $dbh = null;
}

// products_index.php にリダイレクト
header('Location: ./product_index.php');
exit();
