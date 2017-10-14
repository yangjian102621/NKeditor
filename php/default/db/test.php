<?php
/**
 * Created by PhpStorm.
 * User: yangjian
 * Date: 17-10-14
 * Time: 下午5:28
 */
require_once "SimpleDB.php";

$db = new SimpleDB("test");
$db->put(array("name" => "yangjian", "address" => "shenzhen"));

