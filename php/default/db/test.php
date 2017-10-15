<?php
/**
 * Created by PhpStorm.
 * User: yangjian
 * Date: 17-10-14
 * Time: 下午5:28
 */
require_once "SimpleDB.php";

$db = new SimpleDB("test");
$t = 100000000;
for($i = 0; $i < 1000000; $i++) {
    $db->putLine($t+$i);
}
printf("数据插入完毕！\n");
$items = $db->getDataList(2, 10);
print_r($items);

