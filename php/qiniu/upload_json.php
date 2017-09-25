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
$base64 = trim($_POST['base64']);
if ($base64) {
    $data = $_POST['img_base64_data'];
    $filename = "{$fileType}-".time().".png";
    $res = base64Upload($data, $filename, $token);
    $json = new \JsonResult();
    if ($res) {
        $res = json_decode($res, true);
        $json->setCode(\JsonResult::CODE_SUCCESS);
        $json->setItem(QINIU_BUCKET_DOMAIN.$res['key']);
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
        $json->setItem(array('url' => QINIU_BUCKET_DOMAIN . $ret['key']));
    }
    $json->output();
}


function base64Upload($data, $filename, $upToken)
{

    if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $data, $match)) {

        $imgData = str_replace($match[1], '', $data); //去掉图片的声明前缀

        /**
         * upload.qiniu.com 上传域名适用于华东空间。华北空间使用 upload-z1.qiniu.com，
         * 华南空间使用 upload-z2.qiniu.com，北美空间使用 upload-na0.qiniu.com。
         */
        $url = "http://upload-z2.qiniu.com/putb64/-1/key/".base64_encode($filename);
        $headers = array();
        $headers[] = 'Content-Type:image/png';
        $headers[] = 'Authorization:UpToken ' . $upToken;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        //curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $imgData);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }
    return false;

}
