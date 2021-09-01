<?php

namespace app\modules\panel\services;

use app\common\components\Code;
use app\common\components\JsonResult;
use Yii;

class LoginService extends \app\common\base\BaseService
{
    public function login($username, $password)
    {
        if (empty($username) || empty($password)) {
            return JsonResult::arr(Code::$invalid_param);
        }
        /** @var SysAdmin $oAdmin */
        $oAdmin = (new SysAdmin())->getByUsername($username);
        if (empty($oAdmin)) {
            return JsonResult::arr(Code::$sys_admin_not_exists);
        }
        if ($oAdmin->status != SysAdmin::STATUS_SUC) {
            return JsonResult::arr(Code::$sys_admin_is_blacklist);
        }
        if (!$oAdmin->checkPassword($password)) {
            return JsonResult::arr(Code::$sys_admin_password_err);
        }
        AdminService::getInstance()->updateLastInfo($oAdmin);

        $oAdmin->refresh();
        Yii::$app->panel->login($oAdmin);
        return JsonResult::arr(Code::$ok);
    }
}