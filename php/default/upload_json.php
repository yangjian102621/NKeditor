<?php
/****************************************************
 * NKeditor PHP
 * 本PHP程序是演示程序，建议不要直接在实际项目中使用。
 * 如果您确定直接使用本程序，使用之前请仔细确认相关安全设置。
 * **************************************************
 * @author yangjian<yangjian102621@gmail.com>
 * 文件上传程序
 */
error_reporting(0);
require_once '../JsonResult.php';
require_once '../functions.php';
require_once "db/SimpleDB.php";

$fileType = empty($_GET['dir']) ? 'image' : trim($_GET['dir']);
//文件保存目录路径
$basePath = BASE_PATH."{$fileType}/".UPLOAD_PREFIX;
//文件保存目录URL
$baseUrl = BASE_URL . "{$fileType}/".UPLOAD_PREFIX;
//定义允许上传的文件扩展名
$allowExtesions = array(
	'image' => array('gif', 'jpg', 'jpeg', 'png', 'bmp'),
	'flash' => array('swf', 'flv'),
	'media' => array('swf', 'flv', 'mp3', 'wav', 'wma', 'wmv', 'mid', 'avi', 'mpg', 'asf', 'rm', 'rmvb'),
	'file' => array('doc', 'docx', 'xls', 'xlsx', 'ppt', 'htm', 'html', 'txt', 'zip', 'rar', 'gz', 'bz2'),
);
//最大文件大小 2MB
$maxSize = 2*1024*1024;
if (!file_exists($basePath)) {
    mkdirs($basePath);
}
//PHP上传失败
if (!empty($_FILES['imgFile']['error'])) {
	switch($_FILES['imgFile']['error']){
		case '1':
			$error = '超过php.ini允许的大小。';
			break;
		case '2':
			$error = '超过表单允许的大小。';
			break;
		case '3':
			$error = '图片只有部分被上传。';
			break;
		case '4':
			$error = '请选择图片。';
			break;
		case '6':
			$error = '找不到临时目录。';
			break;
		case '7':
			$error = '写文件到硬盘出错。';
			break;
		case '8':
			$error = 'File upload stopped by extension。';
			break;
		case '999':
		default:
			$error = '未知错误。';
	}
	alert($error);
}

//base64 文件上传
$base64 = trim($_POST['base64']);
if ($base64) {
	$imgData = $_POST['img_base64_data'];

	$json = new JsonResult();
	if ($imgData && preg_match('/^(data:\s*image\/(\w+);base64,)/', $imgData, $match)){
		$type = $match[2];
		$filename = date("YmdHis") . '_' . rand(10000, 99999) . '.png';
		if (file_put_contents($basePath.$filename, base64_decode(str_replace($match[1], '', $imgData)))){
			$json->setCode(JsonResult::CODE_SUCCESS);
			$json->setData(array('url' => $baseUrl.$filename));
			$json->output();
		}
	}
	$json->setCode(JsonResult::CODE_FAIL);
	$json->setMessage("涂鸦保存失败.");
	$json->output();
}

// input 文件上传
if (empty($_FILES) == false) {
	//原文件名
	$filename = $_FILES['imgFile']['name'];
	//服务器上临时文件名
	$tmpName = $_FILES['imgFile']['tmp_name'];
	//文件大小
	$filesize = $_FILES['imgFile']['size'];
	//检查文件名
	if (!$filename) {
		alert("请选择文件。");
	}
	//检查目录
	if (@is_dir($basePath) === false) {
		alert("上传目录不存在。");
	}
	//检查目录写权限
	if (@is_writable($basePath) === false) {
		alert("上传目录没有写权限。");
	}
	//检查是否已上传
	if (@is_uploaded_file($tmpName) === false) {
		alert("上传失败。");
	}
	//检查文件大小
	if ($filesize > $maxSize) {
		alert("上传文件大小超过限制。");
	}

	//获得文件扩展名
	$extesion = getFileExt($filename);
	//检查扩展名
	if (in_array($extesion, $allowExtesions[$fileType]) === false) {
		alert("上传文件扩展名是不允许的扩展名。\n只允许" . implode(",", $allowExtesions[$fileType]) . "格式。");
	}
	//新文件名
	$newFileName = genNewFilename($filename);
	//移动文件
	$filePath = $basePath . $newFileName;
	if (move_uploaded_file($tmpName, $filePath) === false) {
		alert("上传文件失败。");
	}
	@chmod($filePath, 0644);
	$fileUrl = $baseUrl . $newFileName;

	$json = new JsonResult(JsonResult::CODE_SUCCESS, "上传成功");
	$json->setData(array('url' => $fileUrl));

	//保存文件地址到数据库
    $db = new SimpleDB($fileType);
    //过滤掉非图片文件
    if ($fileType == "image") {
        $size = getimagesize($filePath);
    }
    $data = [
        "thumbURL" => $fileUrl,
        "oriURL" => $fileUrl,
        "filesize" => $filesize,
        "width" => intval($size[0]),
        "height" => intval($size[1])
    ];
    $db->putLine($data);

	$json->output();
}

function alert($msg) {
	$json = new JsonResult(JsonResult::CODE_FAIL, $msg);
	$json->output();
}
