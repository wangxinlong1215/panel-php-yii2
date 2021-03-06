<?php

namespace app\modules\panel\controllers;

use app\common\components\Code;
use app\common\components\JsonResult;
use app\common\services\AdminService;
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

        [$result, $code, $data] = LoginService::getInstance()->checkLogin($username, $password);
        if (!$result) {
            return JsonResult::error($code);
        }

        AdminService::getInstance()->updateLastInfo($data);

        $data->refresh();
        Yii::$app->panel->login($data);

        return JsonResult::ok();
    }

    /**
     * 登出
     * @return \yii\web\Response
     * @author 王新龙
     * @date   2021-09-03 13:57
     */
    public function actionLogout()
    {
        $adminInfo = $this->getUser();
        if (!empty($adminInfo)) {
            Yii::$app->panel->logout(FALSE);
        }
        return JsonResult::ok();
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