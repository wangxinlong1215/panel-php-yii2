<?php

namespace app\models\data;

use Yii;

class SysMenu extends \app\models\base\SysMenu
{
    CONST STATUS_INIT = 0;//初始
    CONST STATUS_SUC  = 1;//启用
    CONST STATUS_BAN  = 2;//禁用
    CONST STATUS_DEL  = 3;//删除

    CONST SYSTEM_PANEL = 1;//默认系统
}