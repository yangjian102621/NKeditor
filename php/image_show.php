<?php
/**
 * 抓取百度搜索图片服务器上的防盗链图片
 * @author yangjian<yangjian102621@gmail.com>
 */
$img_url = trim($_GET["img_url"]);
$img_path = trim($_GET["img_path"]);
if ( $img_path != "" && $img_url != "" )  {

    $image = file_get_contents($img_url);
    if ( $image ) {
        @file_put_contents($img_path, $image);
        show_image(imagecreatefromstring($image), $img_url);
    } else {
        die("图片加载失败！");
    }
}

function show_image($image, $img_url) {

    $info = pathinfo($img_url);
    switch ( strtolower($info["extension"]) ) {
        case "jpg":
        case "jpeg":
            header('content-type:image/jpg;');
            imagejpeg($image);
            break;

        case "gif":
            header('content-type:image/gif;');
            imagegif($image);
            break;

        case "png":
            header('content-type:image/png;');
            imagepng($image);
            break;

        default:
            header('content-type:image/wbmp;');
            image2wbmp($image);
    }

}
