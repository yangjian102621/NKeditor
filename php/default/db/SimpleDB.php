<?php
/**
 * 简易数据库
 * User: yangjian
 * Date: 17-10-14
 * Time: 下午5:15
 */

class SimpleDB {

    private $handler = null;

    private $dbFile = null;

    private $page = 1;

    private $pagesize = 15;

    private $offset = 0;

    /**
     * 初始化，打开文件
     * SimpleDB constructor.
     * @param $dbname
     */
    public function __construct($dbname)
    {
        $this->handler = fopen(__DIR__."/data/".$dbname, 'a+');
    }

    /**
     * 追加数据
     * @param $data
     * @return bool
     */
    public function put($key, $data) {

        $_data = ['key' => $key, 'data' => $data];
        if ($this->handler != null) {
            fwrite($this->handler, $this->seralize($_data));
        }
        return false;

    }

    public function get() {

    }

    /**
     * 序列化数据
     * @param $data
     * @return string
     */
    private function seralize($data) {

        return json_encode($data, JSON_UNESCAPED_UNICODE);

    }

    /**
     * 反序列化
     * @param $data
     * @return mixed
     */
    private function unseralize($data) {

        return json_decode($data, true);

    }

    /**
     * 关闭文件
     */
    public function __destruct()
    {
        if ($this->handler != null) {
            fclose($this->handler);
        }
    }
}