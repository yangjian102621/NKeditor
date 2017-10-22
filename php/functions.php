<?php
/**
 * 全局函数
 * @author yangjian<yangjian102621@gmail.com>
 */

// 文件上传的根路径
define('BASE_PATH', dirname(__DIR__)."/uploads/");
// 文件上传路径前缀
define('UPLOAD_PREFIX', date('Ym').'/'.date('d').'/');
// 文件上传的根 url
define('BASE_URL', dirname(dirname(dirname($_SERVER['PHP_SELF'])))."/uploads/");

/**
 * 创建多级目录
 * @param $dir
 */
function mkdirs($path) {
    $files = preg_split('/[\/|\\\]/s', $path);
    $_dir = '';
    foreach ($files as $value) {
        $_dir .= $value.DIRECTORY_SEPARATOR;
        if ( !file_exists($_dir) ) {
            mkdir($_dir);
        }
    }
}

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
 * 显示图片
 * @param $image
 * @param $img_url
 */
function show_image($image, $img_url) {

    $info = pathinfo($img_url);
    switch ( strtolower($info["extension"]) ) {
        case "jpg":
        case "jpeg":
            header('content-type:image/jpg;');
            imagejpeg($image);
            break;

        case "gif":
            header('content-type:image/gif;');
            imagegif($image);
            break;

        case "png":
            header('content-type:image/png;');
            imagepng($image);
            break;

        default:
            header('content-type:image/wbmp;');
            image2wbmp($image);
    }

}

/**
 * 生成新的文件名
 * @param $file
 * @return string
 */
function genNewFilename($file) {
    $extesion = getFileExt($file);
    return date("YmdHis") . '_' . rand(10000, 99999) . '.' . $extesion;
}

/**
 * 清空目录
 * @param $dirName
 * @return bool
 */
function deldir($dirName) {
    //节省资源，每天清理一次
    $file = "cache.tmp";
    $t = @file_get_contents($file);
    $now = time();
    if ($now - intval($t) < 60*60*24) {
        return false;
    }
    file_put_contents($file, $now);

    if(file_exists($dirName) && $handle=opendir($dirName)){
        while(false!==($item = readdir($handle))){
            if($item!= "." && $item != ".."){
                if(file_exists($dirName.'/'.$item) && is_dir($dirName.'/'.$item)){
                    delFile($dirName.'/'.$item);
                }else{
                    @unlink($dirName.'/'.$item);
                }
            }
        }
        closedir( $handle);
    }
}
