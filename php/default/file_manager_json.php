<?php
/****************************************************
 * NKeditor PHP
 * 本PHP程序是演示程序，建议不要直接在实际项目中使用。
 * 如果您确定直接使用本程序，使用之前请仔细确认相关安全设置。
 * **************************************************
 * 获取图片服务器上已上传的图片列表
 * @author yangjian<yangjian102621@gmail.com>
 */
error_reporting(0);
require_once '../JsonResult.php';
require_once '../functions.php';
require_once "db/SimpleDB.php";

usleep(500000);
$page = intval($_GET["page"]);
$fileType = trim($_GET['fileType']);
$pagesize = 15;
//读取文件数据
$db = new SimpleDB($fileType);
$files = $db->getDataList($page, $pagesize);
$result = new JsonResult();
if (!empty($files)) {
    $result->setCode(JsonResult::CODE_SUCCESS);
    $result->setData($files);
} else {
    $result->setCode(JsonResult::CODE_FAIL);
}
$result->output();
