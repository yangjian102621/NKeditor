<?php
/**
 * json result vo
 * ---------------------------------------------------------------------
 * @author yangjian<yangjian102621@gmail.com>
 * @since v1.2.1
 */

class JsonResult {

    const CODE_SUCCESS = "000";
    const CODE_FAIL = "001";

    /**
     * 数据载体
     * @var array
     */
    private $data;

    /**
     * 列表数据条数
     * @var int
     */
    private $count;

    /**
     * 当前数据页码
     * @var int
     */
    private $page;

    /**
     * 每页显示数据条数
     * @var int
     */
    private $pagesize;

    /**
     * 附带数据
     * @var mixed
     */
    private $extra;
    /**
     * 错误代码
     * @var string
     */
    private $code = self::CODE_SUCCESS;

    /**
     * 状态码信息
     * @var array
     */
    private static $_CODE_STATUS = [
        self::CODE_SUCCESS => '操作成功.',
        self::CODE_FAIL => '系统开了小差.',
    ];

    /**
     * 消息
     * @var string
     */
    private $message;

    /**
     * JsonResult constructor.
     * @param $code
     * @param $message
     */
    public function __construct($code=null, $message=null){
        $this->setCode($code);
        $this->setMessage($message);
    }

    /**
     * 创建 JsonResult 实例, 并输出
     * @param $code
     * @param $message
     * @return JsonResult
     */
    public static function result($code, $message) {
        $result = new self($code, $message);
        $result->output();
    }

    /**
     * 返回一个成功的 result vo
     * @param string $message
     * @return JsonResult
     */
    public static function success($message='操作成功') {
        $result = new self(self::CODE_SUCCESS, $message);
        $result->output();
    }

    /**
     * 返回一个失败的 result vo
     * @param string $message
     * @return JsonResult
     */
    public static function fail($message='系统开了小差') {
        $result = new self(self::CODE_FAIL, $message);
        $result->output();
    }

    /**
     * 返回jsonp数据格式
     * @param $code
     * @param $message
     * @param $callback
     */
    public static function jsonp($code, $message, $callback){
        $result = new self($code, $message);
        die($callback. "(". $result .")");
    }

    /**
     * @return int
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param int $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @return the $message
     */
    public function getMessage() {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage($message) {
        $this->message = $message;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param array $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @return int
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * @param int $count
     */
    public function setCount($count)
    {
        $this->count = $count;
    }

    /**
     * @return int
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @param int $page
     */
    public function setPage($page)
    {
        $this->page = $page;
    }

    /**
     * @return int
     */
    public function getPagesize()
    {
        return $this->pagesize;
    }

    /**
     * @param int $pagesize
     */
    public function setPagesize($pagesize)
    {
        $this->pagesize = $pagesize;
    }

    /**
     * @return mixed
     */
    public function getExtra()
    {
        return $this->extra;
    }

    /**
     * @param mixed $extra
     */
    public function setExtra($extra)
    {
        $this->extra = $extra;
    }

    /**
     * 判断是否成功
     * @return bool
     */
    public function isSucess() {
        return $this->code == self::CODE_SUCCESS;
    }

    /**
     * 转换字符串
     * @return string
     */
    public function __toString() {
        if ( !$this->getMessage() ) {
            $this->setMessage(self::$_CODE_STATUS[$this->code]);
        }
        return json_encode(array(
            'code'=>$this->getCode(),
            'message'=>$this->getMessage(),
            'count'=>$this->getCount(),
            'page'=>$this->getPage(),
            'pagesize'=>$this->getPagesize(),
            'extra'=>$this->getExtra(),
            'data'=>$this->getData()), JSON_UNESCAPED_UNICODE);
    }

    /**
     * 以json格式输出
     */
    public function output() {
        header('Content-type: application/json;charset=utf-8');
        echo $this;
        die();
    }
}