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
error_reporting(0); //关闭报错输出，以免打乱 json 数据格式

/**
 * 获取图片尺寸
 * @param $filename
 * @return mixed
 */
function getImgSize($filename) {
    $json = file_get_contents(QINIU_BUCKET_DOMAIN."{$filename}?imageInfo");
    return json_decode($json, true);
}

/**
 * 上传 base64 图片
 * @param $data
 * @param $filename
 * @param $upToken
 * @return bool|mixed
 */
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