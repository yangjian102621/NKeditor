<?php
/****************************************************
 * NKeditor PHP
 * 本PHP程序是演示程序，建议不要直接在实际项目中使用。
 * 如果您确定直接使用本程序，使用之前请仔细确认相关安全设置。
 * **************************************************
 * 简易数据库, 单表 100w 条数据，查询一页数据在 0.015 秒左右
 * 缺陷，无法排序，如果要排序的话，那么不适合使用 SimpleDB， 请使用 mysql 或者 mongdb
 * User: yangjian
 * Date: 17-10-14
 * Time: 下午5:15
 */

class SimpleDB {

    /**
     * 文件资源
     * @var null|resource
     */
    private $handler = null;

    /**
     * 初始化，打开文件
     * SimpleDB constructor.
     * @param $dbname
     */
    public function __construct($dbname)
    {
        $dataDir = __DIR__."/data/";
        if (!file_exists($dataDir)) {
            mkdir($dataDir);
        }
        $this->handler = fopen($dataDir.$dbname.'.db', 'a+');
    }

    /**
     * 写入一行数据
     * @return bool
     */
    public function putLine($data) {

        if ($this->handler != null) {
            fwrite($this->handler, $this->seralize($data));
        }
        return false;

    }

    /**
     * 分页获取数据列表
     * @param $key
     * @return array|null
     */
    public function getDataList($page, $pagesize) {

        if($page <= 0) {
            $page = 1;
        }
        $offset = ($page - 1) * $pagesize;
        //循环读取数据
        $datas = [];
        $counter = 0;
        while (!feof($this->handler)) {
            if ($counter < $offset) {
                fgets($this->handler); //移动指针到下一行
                $counter++;
                continue;
            }
            if (count($datas) == $pagesize) {
                break;
            }
            $line = fgets($this->handler);
            if (!empty($line)) {
                $datas[] = $this->unseralize($line);
            }
        }

        return $datas;
    }

    /**
     * 序列化数据
     * @param $data
     * @return string
     */
    private function seralize($data) {

        $break = "\n"; //换行符
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $break = "\r\n";
        }
        return json_encode($data, JSON_UNESCAPED_UNICODE).$break;

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