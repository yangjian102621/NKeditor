<?php
/**
 * KindEditorX PHP 文件管理演示程序
 * @author yangjian
 * @since 4.1.12 (2017-09-12)
 *
 */
error_reporting(0);
require_once 'JsonResult.php';
$page = intval($_GET['page']);
$url = "http://localhost/javascript/AjaxUpload/image_list.php?page=".$page;
$json = file_get_contents($url);
$data = json_decode($json, true);
if (empty($data)) {
    JsonResult::fail("没有获取到数据");
}
$result = new JsonResult();
if ($data['code'] == 0) {
    $result->setCode(JsonResult::CODE_SUCCESS);
    $result->setItems($data['data']);
} else {
    $result->setCode(JsonResult::CODE_FAIL);
}
$result->output();
