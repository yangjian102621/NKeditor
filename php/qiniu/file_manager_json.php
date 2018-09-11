<?php
namespace qiniu\upload;
/****************************************************
 * NKeditor PHP
 * 本PHP程序是演示程序，建议不要直接在实际项目中使用。
 * 如果您确定直接使用本程序，使用之前请仔细确认相关安全设置。
 * **************************************************
 * 获取图片服务器上已上传的图片列表
 * @author yangjian<yangjian102621@gmail.com>
 */

use Qiniu\Auth;
use Qiniu\Storage\BucketManager;

require_once "vendor/autoload.php";
require_once "../JsonResult.php";
require_once "config.php";
require_once "../functions.php";

$page = intval($_GET["page"]);
$fileType = trim($_GET['fileType']);
if ($fileType == '') {
    $fileType = "image";
}
$marker = trim($_GET['marker']); //上次列举返回的位置标记，作为本次列举的起点信息。

if ($marker == "no_records") {
    \JsonResult::fail("没有更多的文件了");
}

// 构建鉴权对象
$auth = new Auth(QINIU_ACCESS_KEY, QINIU_SECRET_KEY);
$bucketManager = new BucketManager($auth);

// 要列取文件的公共前缀
$prefix = $fileType."-";

// 本次列举的条目数
$limit = 15;
$delimiter = '/';
list($ret, $err) = $bucketManager->listFiles(QINIU_TEST_BUCKET, $prefix, $marker, $limit, $delimiter);
$result = new \JsonResult();
if ($err !== null) {
    $result->setCode(\JsonResult::CODE_FAIL);
    $result->setMessage($err);
} else {
    $files = array();
    $result->setCode(\JsonResult::CODE_SUCCESS);
    foreach($ret["items"] as $value) {
        $filename = $value['key'];
        if (strpos($value['mimeType'], 'image') !== false) { //如果是图片则获取尺寸
            $imgSize = getImgSize($value['key']);
        }
        array_push($files, array(
            "thumbURL" => QINIU_BUCKET_DOMAIN.$filename,
            "oriURL" => QINIU_BUCKET_DOMAIN.$filename,
            "filesize" => $value['fsize'],
            "width" => intval($imgSize["width"]),
            "height" => intval($imgSize["height"])));
    }
    $result->setData($files);
    if (array_key_exists('marker', $ret)) {
        $result->setExtra($ret['marker']);
    } else {
        $result->setExtra("no_records");
    }
}
$result->output();
