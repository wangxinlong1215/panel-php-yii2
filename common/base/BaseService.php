<?php

namespace app\common\base;

class BaseService
{
    /**
     * @var array
     */
    private static $_instance = [];

    public $error = ['code' => 999999, 'msg' => '未知错误'];
    public $data = [];

    public function __construct()
    {
    }

    /**
     * 获取实例
     * @return static
     */
    public static function getInstance()
    {
        $className = get_called_class();
        if (empty(self::$_instance[$className])) {
            self::$_instance[$className] = new static();
        }
        return self::$_instance[$className];
    }
}