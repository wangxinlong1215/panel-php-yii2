<?php

namespace app\common\services;

use app\common\base\BaseService;
use app\common\components\Code;
use app\common\components\JsonResult;
use app\models\data\SysAdmin;
use Yii;

class LoginService extends BaseService
{
    /**
     * 检测登录
     *
     * @param $username
     * @param $password
     *
     * @return array
     * @author 王新龙
     * @date   2021-09-03 11:39
     */
    public function checkLogin($username, $password)
    {
        if (empty($username) || empty($password)) {
            return JsonResult::arr(FALSE, Code::$invalid_param);
        }
        /** @var SysAdmin $oAdmin */
        $oAdmin = (new SysAdmin())->getByUsername($username);
        if (empty($oAdmin) || $oAdmin->status == SysAdmin::STATUS_DEL) {
            return JsonResult::arr(FALSE, Code::$admin_not_exists);
        }
        if ($oAdmin->status == SysAdmin::STATUS_BAN) {
            return JsonResult::arr(FALSE, Code::$admin_is_blacklist);
        }
        if (!$oAdmin->checkPassword($password)) {
            return JsonResult::arr(FALSE, Code::$admin_password_err);
        }

        return JsonResult::arr(TRUE, Code::$ok, $oAdmin);
    }
}