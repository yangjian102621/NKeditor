<?php
/**
 * 七牛服务器配置信息
 * User: yangjian
 * Date: 17-9-14
 * Time: 上午11:29
 */
define("QINIU_ACCESS_KEY", "_-BMslq1mPL_zY0KN2iLD1-ym4TcHhQUi0_dDFPB");
define("QINIU_SECRET_KEY", "J_As9ApfpyCpk31l3hOAZe3QQTc8iYlEfdd6-5an");
define("QINIU_TEST_BUCKET", "kindeditor");
define("QINIU_BUCKET_DOMAIN", "http://ow93rpra1.bkt.clouddn.com/");

/**
 * 获取文件后缀名
 * @param $filename
 * @return string
 */
function getFileExt($filename) {
    $temp_arr = explode(".", $filename);
    $file_ext = array_pop($temp_arr);
    return strtolower(trim($file_ext));
}

/**
 * 获取图片尺寸
 * @param $filename
 * @return mixed
 */
function getImgSize($filename) {
    $json = file_get_contents(QINIU_BUCKET_DOMAIN."{$filename}?imageInfo");
    return json_decode($json, true);
}