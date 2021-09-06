<?php

namespace app\modules\panel\controllers;

use app\common\components\Code;
use app\common\helper\Helper;
use app\models\data\SysAdminLog;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

class BaseController extends \app\common\base\BaseController
{
    protected $actions   = ['*'];//Access验证
    protected $except    = [];//Access不验证
    protected $mustlogin = [];//必须登陆
    protected $verbs     = [];//请求方式验证 例如：protected $verbs = ['index' => ['post']];

    protected $error  = [];
    protected $params = [];

    public function behaviors()
    {
        return [
            'access' => [
                'class'  => AccessControl::className(),
                'only'   => $this->actions,
                'except' => $this->except,
                'user'   => 'panel',
                'rules'  => [
                    [
                        'allow'   => FALSE,
                        'actions' => empty($this->mustlogin) ? [] : $this->mustlogin,
                        'roles'   => ['?'], //@游客
                    ],
                    [
                        'allow'   => TRUE,
                        'actions' => empty($this->mustlogin) ? [] : $this->mustlogin,
                        'roles'   => ['@'], //@代表授权用户
                    ]
                ],
            ],
            'verbs'  => [
                'class'   => VerbFilter::className(),
                'actions' => $this->verbs,
            ],

        ];
    }

    public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) {
            return FALSE;
        }

        $this->addLog($action);
        return TRUE;
        $module     = $action->controller->module->id;
        $controller = $action->controller->id;
        $action     = $action->id;

        /**检测token过期时间**/
        $result = $this->checkLogin();
        if (!$result) {
            $path = $module . '/' . $controller . '/' . $action;
            if (!in_array($path, AdminService::getInstance()->whiteList())) {
                if ($this->isPost()) {
                    exit($this->reback(Code::$invalid_session));
                }
                return $this->redirect('/panel/login');
            }
        }

        if (AdminService::getInstance()->checkVisit($module . '/' . $controller . '/*')) {
            return TRUE;
        }
        if (AdminService::getInstance()->checkVisit($module . '/' . $controller . '/' . $action)) {
            return TRUE;
        }
        if ($this->isPost()) {
            exit($this->reback(Code::$access_error));
        }
        $code = Code::$access_error;
        return $this->redirect('/panel/error/index?msg=' . $code['msg']);
    }

    private function checkLogin()
    {
        $oAdmin = $this->getUser();
        if (empty($oAdmin)) {
            //            return FALSE;
        }
        //if ($oAdmin['valid_time'] < date('Y-m-d H:i:s')) {
        //            return FALSE;
        //}
        return TRUE;
    }

    protected function getAdminId()
    {
        return Yii::$app->panel->identity->id;
    }

    protected function getUser()
    {
        return Yii::$app->panel->identity;
    }

    protected function isGuest()
    {
        if (Yii::$app->panel->isGuest) {
            return TRUE;
        }
        return FALSE;
    }

    protected function getCsrf()
    {
        $csrf = Yii::$app->request->getCsrfToken();
        return $csrf;
    }

    private function addLog($action)
    {
        $module     = $action->controller->module->id;
        $controller = $action->controller->id;
        $action     = $action->id;
        $headers    = Yii::$app->request->headers;
        $userAgent  = '';
        if ($headers->has('User-Agent')) {
            $userAgent = $headers->get('User-Agent');
        }

        $gets   = json_encode($this->get());
        $psots  = json_encode($this->post());
        $oAdmin = $this->getUser();
        $isPost = $this->isPost();

        $data = [
            'route'        => $module . '/' . $controller . '/' . $action,
            'url'          => Yii::$app->request->absoluteUrl,
            'user_agent'   => $userAgent,
            'is_post'      => $isPost ? 1 : 2,
            'gets'         => $gets,
            'posts'        => $psots,
            'admin_id'     => !empty($oAdmin) ? $oAdmin['id'] : 0,
            'admin_email'  => !empty($oAdmin) ? $oAdmin['email'] : '',
            'admin_mobile' => !empty($oAdmin) ? $oAdmin['mobile'] : '',
            'ip'           => Helper::getIp(),
        ];

        (new SysAdminLog())->addRecord($data);
    }

    public function checkParams($data, object $method)
    {
        $method->setAttributes($data, FALSE);
        if (!$method->validate()) {
            $error       = $method->getFirstErrors();
            $this->error = reset($error);
            return FALSE;
        }
        $this->params = $method;
        return TRUE;
    }
}