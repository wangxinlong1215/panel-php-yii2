<?php

namespace app\modules\panel\controllers;

use app\common\components\Code;
use app\common\components\JsonResult;
use app\common\services\LoginService;
use app\models\data\SysAdmin;
use Yii;
use yii\helpers\ArrayHelper;

class LoginController extends BaseController
{
    public    $enableCsrfValidation = FALSE;
    public    $layout               = FALSE;
    protected $except               = ['index', 'login', 'logout', 'check', 'admin-info'];
    protected $verbs                = [
        'index'      => ['get'],
        'login'      => ['post'],
        'logout'     => ['get', 'post'],
        'check'      => ['post'],
        'admin-info' => ['post']
    ];

    public function actionIndex()
    {
        $this->view->title = "登录";
        return $this->render('index', [
            'csrf' => $this->getCsrf()
        ]);
    }

    /**
     * 登录
     * @return mixed
     * @author 王新龙
     * @date   2021-09-01 16:42
     */
    public function actionLogin()
    {
        $username = $this->post('username', '');
        $password = $this->post('password', '');

        $a = LoginService::getInstance()->login();
        ob_start();
        echo '<pre>';
        header('Content-type: text/html; charset=utf-8');
        ini_set('xdebug.var_display_max_children', 128);
        ini_set('xdebug.var_display_max_data', 512);
        ini_set('xdebug.var_display_max_depth', 5);
        var_dump($a);die;
        die;

        $oAdmin = (new SysAdmin())->getByUsername($username);
        if (empty($oAdmin) || $oAdmin->status == SysAdmin::STATUS_DEL) {
            return JsonResult::error(Code::$admin_not_exists);
        }
        if ($oAdmin->status == SysAdmin::STATUS_BAN) {
            return JsonResult::error(Code::$admin_is_blacklist);
        }
        if (!$oAdmin->checkPassword($password)) {
            return JsonResult::error(Code::$admin_password_err);
        }

        //注册登录信息
        AdminService::getInstance()->updateLastInfo($oAdmin->id);

        $oAdmin->refresh();
        Yii::$app->panel->login($oAdmin);


        $adminInfo = ArrayHelper::toArray($oAdmin);
        unset($adminInfo['auth_key']);
        unset($adminInfo['password']);
        unset($adminInfo['password_reset_token']);

        //兼容common
        $session = \Yii::$app->session;
        $session->setCookieParams(['httponly' => FALSE]);
        $session->set("userinfo", $adminInfo);

        return JsonResult::returnOk($adminInfo);
    }

    /**
     * 登出
     * @return \yii\web\Response
     * @author 王新龙
     * @date   2019/9/11 4:30 PM
     */
    public function actionLogout()
    {
        /**api方式**/
        $accessToken = $this->post('access_token', '');
        if (!empty($accessToken)) {
            AdminService::getInstance()->updateAccessToken($accessToken);
            Yii::$app->panel->logout(FALSE);
            if ($this->isPost()) {
                return JsonResult::returnOk();
            }
            return $this->redirect('/panel/login');
        }

        /**session方式**/
        $adminInfo = $this->getUser();
        if (!empty($adminInfo)) {
            AdminService::getInstance()->updateAccessToken($adminInfo->access_token);
            Yii::$app->panel->logout(FALSE);
        }
        if ($this->isPost()) {
            return JsonResult::returnOk();
        }
        return $this->redirect('/panel/login');
    }

    /**
     * 检测登陆状态
     * @author 王新龙
     * @date   2019/9/12 3:01 PM
     */
    public function actionCheck()
    {
        $oAdmin = $this->getUser();
        if (empty($oAdmin)) {
            return JsonResult::returnError(Code::$invalid_session);
        }
        $result = $oAdmin->checkPassword('a123456');
        if ($result) {
            return JsonResult::returnError(Code::$admin_check_init_pass_err);
        }
        return JsonResult::returnOk();
    }

    /**
     * 获取管理员信息
     * @author 王新龙
     * @date   2019/9/12 4:23 PM
     */
    public function actionAdminInfo()
    {
        $accessToken = $this->post('access_token', '');
        if (!empty($accessToken)) {
            /**api方式**/
            $adminInfo = (new SysAdmin())->getByAccessToken($accessToken);
        } else {
            /**session方式**/
            $adminInfo = $this->getUser();
        }

        if (empty($adminInfo)) {
            return JsonResult::returnError(Code::$invalid_session);
        }
        $adminInfo = ArrayHelper::toArray($adminInfo);
        unset($adminInfo['auth_key']);
        unset($adminInfo['password']);
        unset($adminInfo['password_reset_token']);

        return JsonResult::returnOk($adminInfo);
    }
}