<?php
/****************************************************
 * NKeditor PHP
 * 本PHP程序是演示程序，建议不要直接在实际项目中使用。
 * 如果您确定直接使用本程序，使用之前请仔细确认相关安全设置。
 * **************************************************
 * 抓取远程图片服务器上的防盗链图片
 * @author yangjian<yangjian102621@gmail.com>
 */
error_reporting(0);
require "../JsonResult.php";
require "../functions.php";

$img_url = trim($_GET["img_url"]);
$tmp_dir = dirname(dirname(__DIR__)) . "/uploads/tmp";
$dist_dir = dirname(dirname(__DIR__)) . "/uploads/image";
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
        $newUrls = [];
        foreach ($urls as $value) {
            $res = $res && copy($tmp_dir."/".basename($value), $dist_dir."/".basename($value));
            array_push($newUrls, $value);
        }
        if ($res) {
            $jsonResult = new JsonResult(JsonResult::CODE_SUCCESS, "抓取图片成功");
            $jsonResult->setItems($newUrls);
            $jsonResult->output();
        } else {
            JsonResult::fail("抓取图片失败");
        }
    }
} else {
    if ($img_url)  {
        //每天清理一次临时目录
        deldir($tmp_dir);
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
