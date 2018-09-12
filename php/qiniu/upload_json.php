<?php
namespace qiniu\upload;
/****************************************************
 * NKeditor PHP
 * 本PHP程序是演示程序，建议不要直接在实际项目中使用。
 * 如果您确定直接使用本程序，使用之前请仔细确认相关安全设置。
 * **************************************************
 * @author yangjian<yangjian102621@gmail.com>
 * 文件上传程序
 */

error_reporting(0);

use Qiniu\Auth;
use Qiniu\Storage\UploadManager;

require_once "vendor/autoload.php";
require_once "../JsonResult.php";
require_once "config.php";
require_once "../functions.php";

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
$base64 = trim($_POST['base64']);
if ($base64) {
    $data = $_POST['img_base64_data'];
    $filename = "{$fileType}-".time().".png";
    $res = base64Upload($data, $filename, $token);
    $json = new \JsonResult();
    if ($res) {
        $res = json_decode($res, true);
        $json->setCode(\JsonResult::CODE_SUCCESS);
        $json->setData(array('url' => QINIU_BUCKET_DOMAIN.$res['key']));
    } else {
        $json->setCode(\JsonResult::CODE_FAIL);
        $json->setMessage("上传涂鸦失败!");
    }
    $json->output();
} else {
    $filePath = $_FILES['imgFile']['tmp_name'];
    $fileExt = getFileExt($_FILES['imgFile']['name']);

    // 返回结果
    $json = new \JsonResult();
    //定义允许上传的文件扩展名
    $extArr = array(
        'image' => array('gif', 'jpg', 'jpeg', 'png', 'bmp'),
        'flash' => array('swf', 'flv'),
        'media' => array('swf', 'flv', 'mp3', 'wav', 'wma', 'wmv', 'mid', 'avi', 'mpg', 'asf', 'rm', 'rmvb'),
        'file' => array('doc', 'docx', 'xls', 'xlsx', 'ppt', 'htm', 'html', 'txt', 'zip', 'rar', 'gz', 'bz2'),
    );
    //最大文件大小 2MB
    $maxSize = 2*1024*1024;
    if (!in_array($fileExt, $extArr[$fileType])) {
        $json->setCode(\JsonResult::CODE_FAIL);
        $json->setMessage("非法的文件后缀名.");
        $json->output();
    }
    if (filesize($filePath) > $maxSize) {
        $json->setCode(\JsonResult::CODE_FAIL);
        $json->setMessage("文件大小超出限制 2MB.");
        $json->output();
    }

    // 上传到七牛后保存的文件名
    $key = $fileType . "-" . time() . mt_rand(1000, 9999) . ".{$fileExt}";

    // 初始化 UploadManager 对象并进行文件的上传。
    $uploadMgr = new UploadManager();

    // 调用 UploadManager 的 putFile 方法进行文件的上传。
    list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);
    if ($err !== null) {
        $json->setCode(\JsonResult::CODE_FAIL);
        $json->setMessage("上传失败.");
    } else {
        $json->setCode(\JsonResult::CODE_SUCCESS);
        $json->setMessage("上传成功.");
        $json->setData(array('url' => QINIU_BUCKET_DOMAIN . $ret['key']));
    }
    $json->output();
}

