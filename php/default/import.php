<?php
/**
 * 从老版本的 kindeditor 升级到 NKeditor，你可能需要把之前的文件初始化插入数据库。
 * 考虑到你可能更改了之前的老版本的文件上传代码，比如存储路径的风格，这里就只个给出一个参考思路：
 * 1. 遍历你的附件存储根目录， 并根据不同的文件类型 image, flash, file, media 分别创建4个数据库;
 * 2. 分别递归遍历这4个文件夹，把数据文件的 url 插入到各自的 SimpleDB 数据库.
 * ----------------------
 * 重建文件索引， 请使用命令行运行 php import.php
 * @author yangjian
 */
error_reporting(0);
require_once "db/SimpleDB.php";
require_once "../functions.php";

// 文件上传的根目录，请根据自己的实际情况修改
$root = $basePath = dirname(dirname(__DIR__)) . "/uploads/";
// 图片上传的根url，请根据实际项目修改
$baseUrl = "/editor/nkeditor/uploads/";

//如果数据库已经存在，则先删除
$datadir = __DIR__.'/db/data';
file_exists($datadir) && deldir($datadir);

chdir($root);
$dirs = glob("*");
foreach ($dirs as $dir) {
    $db = new SimpleDB($dir);
    walkDir($root.$dir, $db, $dir);
}

tprint("数据导入完毕！");

/**
 * 遍历目录，建立路径索引
 * @param $dir
 * @param SimpleDB $db
 * @param $fileType
 */
function walkDir($dir, $db, $fileType) {
    $handler = opendir($dir);
    global $root;
    global $baseUrl;
    while (($filename = readdir($handler)) !== false) {
        if ($filename != '.' && $filename != '..') {
            $filePath = $dir.'/'.$filename;
            if (is_dir($filePath)) {
                walkDir($filePath, $db, $fileType);
                continue;
            }
            $filesize = filesize($filePath);
            //如果是图片则获取尺寸
            if ($fileType == "image") {
                $size = getimagesize($filePath);
            }
            $fileUrl = $baseUrl.str_replace('\\', '/', str_replace($root, '', $filePath));
            $data = [
                "thumbURL" => $fileUrl,
                "oriURL" => $fileUrl,
                "filesize" => $filesize,
                "width" => intval($size[0]),
                "height" => intval($size[1])
            ];
            $db->putLine($data);
            tprint("添加路径： {$fileUrl}");
        }
    }

    closedir($handler);
}

/**
 * 终端打印函数
 * @param $message
 */
function tprint($message) {
    printf("\033[32m\033[1m{$message}\033[0m\n");
}
