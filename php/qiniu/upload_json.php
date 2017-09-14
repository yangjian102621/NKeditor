<?php
namespace qiniu\upload;
/**
 * Created by PhpStorm.
 * User: yangjian
 * Date: 17-9-14
 * Time: 上午10:08
 */

error_reporting(0);

use Qiniu\Auth;
use Qiniu\Storage\UploadManager;

require_once "vendor/autoload.php";
require_once "../JsonResult.php";
require_once "config.php";

$fileType = trim($_GET['fileType']);
if (empty($fileType)) {
    $fileType = "image";
}
// 构建鉴权对象
$auth = new Auth(QINIU_ACCESS_KEY, QINIU_SECRET_KEY);

// 生成上传 Token
$token = $auth->uploadToken(QINIU_TEST_BUCKET);

// 要上传文件的本地路径
//$filePath = './php-logo.png';
$filePath = $_FILES['imgFile']['tmp_name'];
$fileExt = getFileExt($_FILES['imgFile']['name']);

// 上传到七牛后保存的文件名
$key = $fileType."-".time().mt_rand(1000,9999).".{$fileExt}";

// 初始化 UploadManager 对象并进行文件的上传。
$uploadMgr = new UploadManager();

// 调用 UploadManager 的 putFile 方法进行文件的上传。
list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);
// 返回结果
$json = new \JsonResult();
if ($err !== null) {
    $json->setCode(\JsonResult::CODE_FAIL);
    $json->setMessage("上传失败.");
} else {
    $json->setCode(\JsonResult::CODE_SUCCESS);
    $json->setMessage("上传成功.");
    $json->setItem(array('url' => QINIU_BUCKET_DOMAIN.$ret['key']));
}
$json->output();
