<?php
/****************************************************
 * NKeditor PHP
 * 本PHP程序是演示程序，建议不要直接在实际项目中使用。
 * 如果您确定直接使用本程序，使用之前请仔细确认相关安全设置。
 * **************************************************
 * 从360服务器上搜索图片并返回图片地址列表
 * @author yangjian<yangjian102621@gmail.com>
 */
error_reporting(0);
set_time_limit(0);
require_once '../JsonResult.php';
require_once '../functions.php';

$page = intval($_GET["page"]);
$kw = trim($_GET['kw']);
$apiUrl = "http://image.so.com/j?q={$kw}&src=tab_www&sn={$page}&pn=15";
$content = file_get_contents($apiUrl);
$data = json_decode(mb_convert_encoding($content, 'UTF-8','GBK,UTF-8'), true);
$files = array();
if ( is_array($data["list"]) ) {
    foreach ( $data["list"] as $value ) {
        $filename = basename($value["img"]);
        //这里为了防止搜索的图片禁止盗链，前端无法显示，这里提供一个图片抓取的后端页面
        array_push($files, array(
            "thumbURL" => dirname($_SERVER['PHP_SELF'])."/image_grap_json.php?img_url={$value["img"]}",
            "oriURL" => BASE_URL.'image/'.UPLOAD_PREFIX.$filename,
            "width" => $value["width"],
            "height" => $value["height"]));
    }
}

$result = new JsonResult();
if (!empty($files)) {
    $result->setCode(JsonResult::CODE_SUCCESS);
    $result->setItems($files);
} else {
    $result->setCode(JsonResult::CODE_FAIL);
}
$result->output();