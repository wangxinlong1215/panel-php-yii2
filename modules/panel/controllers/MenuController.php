<?php

namespace app\modules\panel\controllers;

use Yii;

class MenuController extends BaseController
{
    public    $layout = 'frame';
    protected $verbs  = [
        'list' => ['get'],
        'add'  => ['get', 'post'],
        'edit' => ['get', 'post'],
        'del'  => ['post']
    ];

    public function actionList()
    {
        return $this->render('list', [
                'csrf' => $this->getCsrf(),
                'data' => []
            ]
        );
    }

    public function actionAdd()
    {

    }

    public function actionEdit()
    {

    }

    public function actionDel()
    {

    }
}