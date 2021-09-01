<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "tb_admin_log".
 *
 * @property int $id
 * @property string $route
 * @property string $url
 * @property string $user_agent
 * @property int $is_post 1是post请求 2不是post请求
 * @property string $gets
 * @property string $posts
 * @property int $admin_id
 * @property string $admin_email
 * @property string $admin_mobile
 * @property string $admin_ip
 * @property string $created_at
 * @property string $updated_at
 */
class AdminLog extends \app\common\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tb_admin_log';
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
            [['is_post', 'gets', 'posts', 'created_at', 'updated_at'], 'required'],
            [['is_post', 'admin_id'], 'integer'],
            [['gets', 'posts'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['route', 'url', 'user_agent'], 'string', 'max' => 255],
            [['admin_email', 'admin_ip'], 'string', 'max' => 128],
            [['admin_mobile'], 'string', 'max' => 32],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'route' => 'Route',
            'url' => 'Url',
            'user_agent' => 'User Agent',
            'is_post' => 'Is Post',
            'gets' => 'Gets',
            'posts' => 'Posts',
            'admin_id' => 'Admin ID',
            'admin_email' => 'Admin Email',
            'admin_mobile' => 'Admin Mobile',
            'admin_ip' => 'Admin Ip',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
