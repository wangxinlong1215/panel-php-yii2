<?php

namespace app\modules\panel;

use Yii;

/**
 * events module definition class
 */
class Module extends \yii\base\Module
{
    public $controllerNamespace = 'app\modules\activity\controllers';

    public function init()
    {
        $route = Yii::$app->request->absoluteUrl;
        Yii::$app->on(\yii\base\Application::EVENT_BEFORE_ACTION, function ($event) use ($route) {
            Yii::info(json_encode(Yii::$app->request->post(), JSON_UNESCAPED_UNICODE), 'request');
        });
        Yii::$app->on(\yii\base\Application::EVENT_AFTER_ACTION, function ($event) use ($route) {
            Yii::info(json_encode(Yii::$app->getResponse()->data, JSON_UNESCAPED_UNICODE), 'response');
        });

        // module 内配置重写
        Yii::$app->setComponents([
            'request'  => [
                'class'                  => 'yii\web\Request',
                'enableCookieValidation' => FALSE,
                'enableCsrfValidation'   => FALSE,
                'parsers'                => [
                    'application/json' => 'yii\web\JsonParser',
                ],
            ],
            'response' => [
                'class'  => 'yii\web\Response',
                'format' => 'json',
            ],
        ]);
    }

    public function beforeAction($action)
    {
        $action->controller->layout = "";
        return parent::beforeAction($action);
    }
}
