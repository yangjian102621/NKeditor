<?php
/**
 * 全局函数
 * @author yangjian<yangjian102621@gmail.com>
 */

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