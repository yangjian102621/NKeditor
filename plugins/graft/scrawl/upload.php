<?php
/**
 * 文件上传
 * User: yangjian
 * Date: 17-9-19
 * Time: 下午3:07
 */
$imgData = $_POST['imgBase64'];

if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $imgData, $match)){
    $type = $match[2];
    $filename = "files/img-".time().".{$type}";
    if (file_put_contents($filename, base64_decode(str_replace($match[1], '', $imgData)))){
        echo json_encode(array("code" => "000", "url" => $filename), JSON_UNESCAPED_UNICODE);
    } else {
        echo json_encode(array("code" => "000", "url" => "文件保存失败"), JSON_UNESCAPED_UNICODE);
    }
}
die();