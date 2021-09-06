<?php

namespace app\common\services;

use app\common\base\BaseService;
use app\models\data\SysMenu;
use Yii;

class MenuService extends BaseService
{
    public function listMenu()
    {
        $where = [
            'system' => SysMenu::SYSTEM_PANEL,
            'status' => SysMenu::STATUS_SUC,
        ];
        $sort  = [
            'parent_id' => SORT_ASC,
            'sort'      => SORT_DESC
        ];
        $list  = (new SysMenu())->find()->where($where)->orderBy($sort)->asArray()->all();
    }
}