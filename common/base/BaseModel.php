<?php

namespace app\common\base;

use Yii;

class BaseModel extends \yii\db\ActiveRecord
{
    public    $errinfo;
    protected $transaction;

    /**
     * 新增记录
     *
     * @param $data
     *
     * @return bool
     * @author 王新龙
     * @date   2021-08-01 22:14
     */
    public function addRecord($data)
    {
        //1 检测数据是否有效
        if (empty($data) || !is_array($data)) {
            return FALSE;
        }

        $this->setIsNewRecord(TRUE);

        $time               = date('Y-m-d H:i:s');
        $data['created_at'] = $time;
        $data['updated_at'] = $time;

        //2  设置当前类为可添加的, 并检测是否有错误发生
        if ($errors = $this->chkAttributes($data)) {
            return $this->returnError(FALSE, $errors);
        }

        //3 保存数据并返回结果
        if (!$this->save()) {
            return $this->returnError(FALSE, $this->errors);
        }
        return TRUE;
    }

    /**
     * 新增记录
     *
     * @param $data
     *
     * @return bool
     * @author 王新龙
     * @date   2021-09-01 17:30
     */
    public function updateRecord($data)
    {
        if (empty($data) || !is_array($data)) {
            return FALSE;
        }
        $data['updated_at'] = date('Y-m-d H:i:s');
        if ($errors = $this->chkAttributes($data)) {
            return $this->returnError(FALSE, implode('|', $errors));
        }
        return $this->save();
    }

    /**
     * 批量新增记录
     *
     * @param $values
     *
     * @return bool|int
     * @throws \yii\db\Exception
     * @author 王新龙
     * @date   2021-08-01 22:14
     */
    public static function insertBatch($values)
    {
        if (!is_array($values) || !is_array($values[0])) {
            return FALSE;
        }
        $columns = array_keys($values[0]);
        $vs      = [];
        foreach ($values as $v) {
            $temp = [];
            foreach ($columns as $name) {
                $temp[] = $v[$name];
            }
            $vs[] = $temp;
        }

        $db      = static::getDb();
        $command = $db->createCommand()->batchInsert(static::tableName(), $columns, $vs);
        return $command->execute();
    }

    /**
     * 查询记录，根据主键
     *
     * @param $id
     *
     * @return BaseModel|null
     * @author 王新龙
     * @date   2021-08-01 22:14
     */
    public function getById($id)
    {
        return static::findOne($id);
    }

    /**
     * 批量查询记录，根据主键
     *
     * @param $ids
     *
     * @return BaseModel[]|null
     * @author 王新龙
     * @date   2021-08-01 22:15
     */
    public function listByIds($ids)
    {
        if (!is_array($ids)) {
            return NULL;
        }
        return static::findAll($ids);
    }

    /**
     * 封装规则检查
     *
     * @param $postData
     *
     * @return array|null
     * @author 王新龙
     * @date   2021-08-01 22:16
     */
    public function chkAttributes($postData)
    {
        $this->attributes = $postData;

        // 当提交无错误时
        if ($this->validate()) {
            return NULL;
        }

        // 有错误时,只取第一个错误就ok了
        $errors = [];
        foreach ($this->errors as $attribute => $es) {
            $errors[$attribute] = $es[0];
        }
        return $errors;
    }

    /**
     * 事物处理封装，开启事物
     * @author 王新龙
     * @date   2021-08-01 22:16
     */
    protected function beginTransaction()
    {
        $this->transaction = Yii::$app->db_shop->beginTransaction();
    }

    /**
     * 事物处理封装，提交/回滚
     *
     * @param $ok
     *
     * @author 王新龙
     * @date   2021-08-01 22:16
     */
    protected function endTransaction($ok)
    {
        if ($ok) {
            $this->transaction->commit();
        } else {
            $this->transaction->rollBack();
        }
    }

    protected function returnError($result, $errinfo)
    {
        $this->errinfo = $errinfo;
        return $result;
    }
}