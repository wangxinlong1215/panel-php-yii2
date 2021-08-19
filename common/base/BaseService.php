<?php

namespace app\common\base;

use Yii;

class BaseService
{
    /**
     * @var array
     */
    private static $_instance = [];

    public function __construct()
    {
    }

    /**
     * 获取实例
     * @return mixed
     * @author 王新龙
     * @date   2021-08-01 22:09
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