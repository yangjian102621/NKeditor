<?php
/**
 * 从360服务器上搜索图片并返回图片地址列表
 * @author yangjian<yangjian102621@gmail.com>
 */
error_reporting(0);
set_time_limit(0);
require_once 'JsonResult.php';
$page = intval($_GET["page"]);
$kw = trim($_GET['kw']);
$apiUrl = "http://image.so.com/j?q={$kw}&src=tab_www&sn={$page}&pn=15";
$content = file_get_contents($apiUrl);
$data = json_decode(mb_convert_encoding($content, 'UTF-8','GBK,UTF-8'), true);
$files = array();
if ( is_array($data["list"]) ) {
    foreach ( $data["list"] as $value ) {
        $filename = basename($value["thumb"]);
        $baseUrl = dirname($_SERVER['PHP_SELF']);
        //这里为了防止搜索的图片禁止盗链，前端无法显示，这里提供一个图片抓取的后端页面
        array_push($files, array(
            "thumbURL" => "php/image_grap_json.php?img_url={$value["thumb"]}",
            "oriURL" => "{$baseUrl}/files/".$filename,
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