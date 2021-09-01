<?php

namespace app\common\components;

use Yii;

class JsonResult
{
    public static $data = [];

    /**
     * 返回数组
     *
     * @param array  $code
     * @param array  $data
     * @param string $msg
     *
     * @return array
     * @author 王新龙
     * @date   2021-09-01 16:51
     */
    public static function arr(array $code, $data = NULL, $msg = '')
    {
        self::$data['code'] = $code['code'];
        self::$data['msg']  = empty($msg) ? $code['msg'] : $msg;
        if ($data !== NULL) {
            self::$data['data'] = $data;
        } else {
            self::$data['data'] = (object)[];
        }
        return self::$data;
    }

    /**
     * 返回成功
     *
     * @param null $data
     *
     * @return \yii\console\Response|\yii\web\Response
     * @author 王新龙
     * @date   2021-09-01 16:52
     */
    public static function ok($data = NULL)
    {
        return self::build(Code::$ok, '', $data);
    }

    /**
     * 返回错误信息
     *
     * @param array  $code
     * @param string $msg
     *
     * @return \yii\console\Response|\yii\web\Response
     * @author 王新龙
     * @date   2021-09-01 16:49
     */
    public static function error(array $code, $msg = '')
    {
        return self::build($code, $msg);
    }

    public static function build(array $code, $msg = '', $data = NULL)
    {
        self::$data['code'] = $code['code'];
        self::$data['msg']  = empty($msg) ? $code['msg'] : $msg;
        if ($data !== NULL) {
            self::$data['data'] = $data;
        } else {
            self::$data['data'] = (object)[];
        }
        return self::ajaxReturn();
    }

    public static function ajaxReturn()
    {
        $response         = Yii::$app->getResponse();
        $response->format = 'json';
        $response->data   = self::$data;
        return $response;
    }
}