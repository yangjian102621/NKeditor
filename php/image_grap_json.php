<?php
/**
 * 抓取百度搜索图片服务器上的防盗链图片
 * @author yangjian<yangjian102621@gmail.com>
 */
error_reporting(0);
require "JsonResult.php";

$img_url = trim($_GET["img_url"]);
$tmp_dir = dirname(__FILE__)."/tmp";
$dist_dir = dirname(__FILE__)."/files";
if (!file_exists($tmp_dir)) {
    mkdir($tmp_dir);
}
$act = trim($_GET['act']);
if ($act == "grapImage") { //抓取图片
    $urls = explode(",", $_GET["urls"]);
    if (empty($urls)) {
        JsonResult::fail("抓取图片失败");
    } else {
        $res = true;
        foreach ($urls as $value) {
            $res = $res && copy($tmp_dir."/".basename($value), $dist_dir."/".basename($value));
        }
        if ($res) {
            JsonResult::success("抓取图片成功");
        } else {
            JsonResult::fail("抓取图片失败");
        }
    }
} else {
    if ($img_url)  {
        //每天清理一次临时目录
        delFile($tmp_dir);
        $filename = basename($img_url);
        $image = file_get_contents($img_url);
        if ($image != false) {
            //抓取后的图片存入临时文件夹
            @file_put_contents($tmp_dir."/".$filename, $image);
            show_image(imagecreatefromstring($image), $img_url);
        } else {
            die("图片加载失败！");
        }
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

/**
 * 清空目录
 * @param $dirName
 * @return bool
 */
function delFile($dirName) {
    //节省资源，每天清理一次
    $file = "cache.tmp";
    $t = @file_get_contents($file);
    $now = time();
    if ($now - intval($t) < 60*60*24) {
        return false;
    }
    file_put_contents($file, $now);

    if(file_exists($dirName) && $handle=opendir($dirName)){
        while(false!==($item = readdir($handle))){
            if($item!= "." && $item != ".."){
                if(file_exists($dirName.'/'.$item) && is_dir($dirName.'/'.$item)){
                    delFile($dirName.'/'.$item);
                }else{
                    @unlink($dirName.'/'.$item);
                }
            }
        }
        closedir( $handle);
    }
}
