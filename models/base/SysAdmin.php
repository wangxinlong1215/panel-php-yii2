<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "tb_sys_admin".
 *
 * @property int $id
 * @property string $username 用户名
 * @property string $password 用户密码
 * @property string $name 真实姓名
 * @property string $head_pic 头像
 * @property string $email 邮箱
 * @property string $mobile 手机号
 * @property int $status 状态 1正常 2禁用
 * @property string|null $last_login_time 最后登陆时间
 * @property string|null $last_login_ip 最后登陆IP
 * @property string $created_at 创建时间
 * @property string $updated_at 最后更新时间
 * @property string $version
 */
class SysAdmin extends \app\common\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tb_sys_admin';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_master');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status'], 'integer'],
            [['last_login_time', 'created_at', 'updated_at'], 'safe'],
            [['created_at', 'updated_at'], 'required'],
            [['username', 'name'], 'string', 'max' => 32],
            [['password', 'head_pic'], 'string', 'max' => 255],
            [['email'], 'string', 'max' => 128],
            [['mobile', 'last_login_ip'], 'string', 'max' => 16],
            [['version'], 'string', 'max' => 11],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'password' => 'Password',
            'name' => 'Name',
            'head_pic' => 'Head Pic',
            'email' => 'Email',
            'mobile' => 'Mobile',
            'status' => 'Status',
            'last_login_time' => 'Last Login Time',
            'last_login_ip' => 'Last Login Ip',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'version' => 'Version',
        ];
    }
}
