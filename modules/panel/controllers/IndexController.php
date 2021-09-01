<?php

namespace app\modules\panel\controllers;

class IndexController extends BaseController
{
    public $layout = FALSE;

    public function actionIndex()
    {
        echo 1;die;
        $this->view->title = "登录";
        return $this->render('index', [
            'csrf' => $this->getCsrf()
        ]);
    }

    public function actionWelcome()
    {
        $this->view->title = "welcome";
        return $this->render('welcome', [
            'csrf' => $this->getCsrf()
        ]);
    }
}