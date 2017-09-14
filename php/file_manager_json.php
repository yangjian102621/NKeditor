<?php
/**
 * 获取图片服务器上已上传的图片列表
 * @author yangjian<yangjian102621@gmail.com>
 */
error_reporting(0);
require_once 'JsonResult.php';
usleep(500000);
$page = intval($_GET["page"]);
$fileType = trim($_GET['fileType']);
$offset = ($page - 1) * 15;
$image_dir = __DIR__."/files";
$files = array();
$handler = opendir($image_dir);
if ( $handler != false ) {
    $i = 0;
    while ( $filename = readdir($handler) ) {
        if ( $filename != "." && $filename != ".." ) {
            if ( $i < $offset ) {
                $i++;
                continue;
            }
            $size = getimagesize("files/".$filename);
            //过滤掉非图片文件
            if ($fileType == "image" && empty($size)) {
                continue;
            }
            if ($fileType != "image" && !empty($size)) {
                continue;
            }
            $filesize = filesize("files/".$filename);
            array_push($files, array("thumbURL" => dirname($_SERVER['PHP_SELF'])."/files/".$filename, "oriURL" =>
                dirname($_SERVER['PHP_SELF'])."/files/".$filename,
                "filesize" => $filesize,
                "width" => intval($size[0]),
                "height" => intval($size[1])));
            $i++;
            if ( $i > $offset + 15 ) break;
        }
    }
    closedir($handler);
}
$result = new JsonResult();
if (!empty($files)) {
    $result->setCode(JsonResult::CODE_SUCCESS);
    $result->setItems($files);
} else {
    $result->setCode(JsonResult::CODE_FAIL);
}
$result->output();
