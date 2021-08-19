<?php

namespace app\common\base;

use yii\web\Controller;
use yii;

class BaseController extends Controller
{
    /**
     * 检测post请求
     * @return bool|mixed
     * @author 王新龙
     * @date   2021-08-01 22:13
     */
    protected function isPost()
    {
        return Yii::$app->request->isPost;
    }

    /**
     * 获取get信息
     *
     * @param null $name
     * @param null $defaultValue
     *
     * @return array|mixed|string
     * @author 王新龙
     * @date   2021-08-01 22:13
     */
    protected function get($name = NULL, $defaultValue = NULL)
    {
        $v = Yii::$app->request->get($name, $defaultValue);
        $v = $v ? $this->trim($v) : $v;
        return $v;
    }

    /**
     * 获取post信息
     *
     * @param null $name
     * @param null $defaultValue
     *
     * @return array|mixed|string
     * @author 王新龙
     * @date   2021-08-01 22:13
     */
    protected function post($name = NULL, $defaultValue = NULL)
    {

        $v = Yii::$app->request->post($name, $defaultValue);
        $v = $this->trim($v);
        return $v;
    }

    /**
     * 先接post信息，如null，再接get信息
     *
     * @param      $name
     * @param null $defaultValue
     *
     * @return array|mixed|string
     * @author 王新龙
     * @date   2021-08-01 22:13
     */
    protected function getParam($name, $defaultValue = NULL)
    {
        $v = $this->post($name);
        if (is_null($v)) {
            $v = $this->get($name, $defaultValue);
        }
        $v = $v ? $this->trim($v) : $v;
        return $v;
    }

    /**
     * 去除空格
     *
     * @param $string
     *
     * @return array|string
     * @author 王新龙
     * @date   2021-08-01 22:13
     */
    protected function trim($string)
    {
        if (!is_array($string)) {
            return trim($string);
        }
        foreach ($string as $key => $val) {
            $string[$key] = $this->trim($val);
        }
        return $string;
    }
}