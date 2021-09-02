<?php

namespace app\common\services;

use app\common\base\BaseService;
use app\common\components\Code;
use app\common\components\JsonResult;
use app\models\data\SysAdmin;
use Yii;

class LoginService extends BaseService
{
    public function login($username, $password)
    {
        echo 1;die;

        if (empty($username) || empty($password)) {
            return JsonResult::arr(Code::$invalid_param);
        }
        /** @var SysAdmin $oAdmin */
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
//        AdminService::getInstance()->
//            ->updateLastInfo($oAdmin);

        $oAdmin->refresh();
        Yii::$app->panel->login($oAdmin);
        return JsonResult::arr(Code::$ok);
    }
}