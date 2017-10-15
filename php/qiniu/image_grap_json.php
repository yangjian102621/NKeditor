<?php
/****************************************************
 * NKeditor PHP
 * 本PHP程序是演示程序，建议不要直接在实际项目中使用。
 * 如果您确定直接使用本程序，使用之前请仔细确认相关安全设置。
 * **************************************************
 * 抓取远程图片服务器上的防盗链图片
 * @author yangjian<yangjian102621@gmail.com>
 */
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;

require_once "vendor/autoload.php";
require_once "../JsonResult.php";
require_once "config.php";
require_once "../functions.php";

$img_url = trim($_GET["img_url"]);
$tmp_dir = dirname(dirname(__DIR__)) . "/uploads/tmp";

if (!file_exists($tmp_dir)) {
    mkdir($tmp_dir);
}
$act = trim($_GET['act']);
if ($act == "grapImage") { //抓取图片
    $urls = explode(",", $_GET["urls"]);
    if (empty($urls)) {

        JsonResult::fail("抓取图片失败");

    } else { //抓取图片上传到七牛空间
        $res = false;
        // 构建鉴权对象
        $auth = new Auth(QINIU_ACCESS_KEY, QINIU_SECRET_KEY);
        $token = $auth->uploadToken(QINIU_TEST_BUCKET);
        $newUrls = [];
        foreach ($urls as $value) {
            $filePath = $tmp_dir."/".basename($value);
            $fileExt = getFileExt($value);
            $key = "image-".time().mt_rand(1000,9999).".{$fileExt}";
            $uploadMgr = new UploadManager();
            list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);
            $res = $ret || ($err === null); //只要一个抓取成功就代表操作成功
            if ($err === null) {
                array_push($newUrls, QINIU_BUCKET_DOMAIN.$ret['key']);
            }
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
