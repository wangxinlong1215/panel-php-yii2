<?php

namespace app\models\data;

use Yii;

class SysAdmin extends \app\models\base\SysAdmin
{
    CONST SUPER_ADMIN = 'admin';

    CONST STATUS_INIT = 0;//初始
    CONST STATUS_SUC  = 1;//启用
    CONST STATUS_BAN  = 2;//禁用
    CONST STATUS_DEL  = 3;//删除

    /**
     * 查询记录，根据username
     *
     * @param $username
     *
     * @return array|null|\yii\db\ActiveRecord
     * @author 王新龙
     * @date   2021-09-01 16:56
     */
    public function getByUsername($username)
    {
        if (empty($username)) {
            return [];
        }
        return self::find()->where(['username' => $username])->one();
    }

    /**
     * 检测hash密码
     *
     * @param $password
     *
     * @return bool
     * @author 王新龙
     * @date   2021-09-01 16:59
     */
    public function checkPassword($password)
    {
        if (empty($password)) {
            return FALSE;
        }
        $bool = Yii::$app->getSecurity()->validatePassword($password, $this->password);
        if (!$bool) {
            return FALSE;
        }
        return TRUE;
    }
}