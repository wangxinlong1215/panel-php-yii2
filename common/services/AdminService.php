<?php

namespace app\common\services;

use app\common\base\BaseService;
use app\common\helper\Helper;
use app\models\data\SysAdmin;
use Yii;

class AdminService extends BaseService
{
    /**
     * 更新管理员登陆时间&登陆IP
     *
     * @param SysAdmin $oAdmin
     *
     * @return bool
     * @author 王新龙
     * @date   2021-09-01 17:28
     */
    public function updateLastInfo(SysAdmin $oAdmin)
    {
        if (empty($oAdmin)) {
            return FALSE;
        }
        $ip   = Helper::getIp();
        $data = [
            'last_login_date' => date('Y-m-d H:i:s'),
            'last_login_ip'   => $ip
        ];
        return $oAdmin->updateRecord($data);
    }
}