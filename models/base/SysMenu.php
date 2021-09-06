<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "tb_sys_menu".
 *
 * @property int $id
 * @property int $parent_id 父级ID
 * @property string $icon icon
 * @property string $name 菜单名称
 * @property string $route 接口路径
 * @property string $path 前端路径
 * @property int $sort 排序
 * @property int $system 所属系统
 * @property int $status 状态 1正常;2删除
 * @property string $created_at 创建时间
 * @property string $updated_at 更新时间
 * @property int $version
 */
class SysMenu extends \app\common\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tb_sys_menu';
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
            [['parent_id', 'sort', 'system', 'status', 'version'], 'integer'],
            [['created_at', 'updated_at'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['icon', 'name'], 'string', 'max' => 128],
            [['route', 'path'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'parent_id' => 'Parent ID',
            'icon' => 'Icon',
            'name' => 'Name',
            'route' => 'Route',
            'path' => 'Path',
            'sort' => 'Sort',
            'system' => 'System',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'version' => 'Version',
        ];
    }
}
