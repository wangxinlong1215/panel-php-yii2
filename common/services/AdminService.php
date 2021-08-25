<?php

namespace app\modules\panel\services;

use ada\base\BaseService;
use ada\components\redis\Redis;
use app\components\Code;
use app\components\Helper;
use app\components\JsonResult;
use app\models\Admin;
use app\models\AuthAssignment;
use app\models\AuthItemChild;
use app\models\AuthRole;
use yii\helpers\ArrayHelper;
use Yii;

class AdminService extends BaseService
{
    public $error = ['code' => 999999, 'msg' => '未知错误'];
    public $data = [];

    /**
     * 列表记录
     *
     * @param $params
     *
     * @return array
     * @author 王新龙
     * @date   2019/12/11 5:16 PM
     */
    public function listRecord($params)
    {
        $fieldArr = ['page', 'limit'];
        $result   = Helper::checkRequired($fieldArr, $params);
        if (!$result) {
                return JsonResult::returnArr(Code::$invalid_param);
        }
        $page  = ArrayHelper::getValue($params, 'page', 1) - 1;
        $limit = ArrayHelper::getValue($params, 'limit', 10);

        $where = $this->getListWhere($params);
        $order = [
            'id' => SORT_DESC
        ];

        $sql   = (new Admin())->find()->where($where);
        $count = $sql->count();
        $list  = $sql->orderBy($order)->offset($page * $limit)->limit($limit)->asArray(TRUE)->all();

        $list = $this->filterList($list);


        $data = [
            'count' => (int)$count,
            'data'  => $list
        ];
        return $data;
    }

    /**
     * 管理员信息
     *
     * @param $id
     *
     * @return array
     * @author 王新龙
     * @date   2019/12/12 11:54 AM
     */
    public function show($id)
    {
        if (empty($id)) {
            return JsonResult::returnArr(Code::$invalid_param);
        }
        $oAdmin = (new Admin())->getById($id);
        if (empty($oAdmin)) {
            return JsonResult::returnArr(Code::$admin_not_exists);
        }

        if ($oAdmin->username == Admin::SUPER_ADMIN) {
            return JsonResult::returnArr(Code::$admin_super_error);
        }
        $oRole   = (new AuthAssignment())->listByAdminId($oAdmin->id);
        $roleArr = [];
        if (!empty($oRole)) {
            $roleArr = ArrayHelper::toArray($oRole);
            $roleArr = array_column($roleArr, 'role_id');
        }

        $roleList = (new AuthRole())->listByType();
        if (!empty($roleList)) {
            $roleList = ArrayHelper::toArray($roleList);
            foreach ($roleList as &$item) {
                $item['is_select'] = 2;
                if (in_array($item['id'], $roleArr)) {
                    $item['is_select'] = 1;
                }
            }
        }
        $data = [
            'admin_info' => ArrayHelper::toArray($oAdmin),
            'role_list'  => $roleList
        ];
        return JsonResult::returnArr(Code::$ok, $data);
    }

    /**
     * 更改管理员状态
     *
     * @param $id
     * @param $status
     *
     * @return array|bool
     * @author 王新龙
     * @date   2019/9/20 3:00 PM
     */
    public function updateStatus($id, $status)
    {
        if (empty($id) || empty($status)) {
            return FALSE;
        }
        $oAdmin = (new Admin())->getById($id);
        if (empty($oAdmin)) {
            return JsonResult::returnArr(Code::$admin_not_exists);
        }

        if ($oAdmin->username == Admin::SUPER_ADMIN) {
            return JsonResult::returnArr(Code::$admin_super_error);
        }

        $data = [
            'status' => $status
        ];
        if ($status == Admin::STATUS_PROHIBIT) {
            $data['access_token'] = Helper::getRandStr($oAdmin->username);
        }
        $result = $oAdmin->updateRecord($data);
        if (!$result) {
            return JsonResult::returnArr(Code::$edit_error);
        }
        return JsonResult::returnArr(Code::$ok);
    }

    /**
     * 修改密码
     *
     * @param $params
     *
     * @return array|bool
     * @throws \yii\base\Exception
     * @author 王新龙
     * @date   2019/12/2 4:50 PM
     */
    public function updatePass($params)
    {
        $fieldArr = ['admin_id', 'current_password', 'password'];
        $result   = Helper::checkRequired($fieldArr, $params);
        if (!$result) {
            $this->error = Code::$invalid_param;
            return FALSE;
        }

        $oAdmin = (new Admin())->getById($params['admin_id']);
        if (empty($oAdmin)) {
            $this->error = Code::$admin_not_exists;
            return FALSE;
        }

        /**检测密码强度**/
        $result = $this->checkPass($params['password']);
        if (!$result) {
            $this->error = Code::$admin_check_pass_err;
            return FALSE;
        }

        /**检测旧密码**/
        if (!$oAdmin->checkPassword($params['current_password'])) {
            $this->error = Code::$admin_check_ole_pass_err;
            return FALSE;
        }

        /**检测新旧密码是否相同**/
        if ($params['current_password'] == $params['password']) {
            $this->error = Code::$admin_check_alike_pass_err;
            return FALSE;
        }

        /**修改密码**/
        $data   = [
            'password'   => (new Admin())->encryption($params['password']),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        $result = $oAdmin->updateRecord($data);
        if (!$result) {
            $this->error = Code::$add_error;
            return FALSE;
        }
        return TRUE;
    }

    /**
     * 检测密码强度
     *
     * @param $password
     *
     * @return array|bool
     * @author 王新龙
     * @date   2019/11/30 3:46 PM
     */
    public function checkPass($password)
    {
        if (empty($password)) {
            return FALSE;
        }
        $r1 = '/[A-Za-z]/';
        $r2 = '/[0-9]/';
        $r3 = '/[~!@#$%^&*()\-_=+{};:<,.>?]/';

        if (strlen($password) < 6 || strlen($password) > 20) {
            return FALSE;
        }
        $result = [];
        if (preg_match_all($r1, $password, $o) < 1) {
            $result[] = FALSE;
        }
        if (preg_match_all($r2, $password, $o) < 1) {
            $result[] = FALSE;
        }
        if (preg_match_all($r3, $password, $o) < 1) {
            $result[] = FALSE;
        }
        if (count($result) > 1) {
            return FALSE;
        }
        return TRUE;
    }

    /**
     * 更新管理员登陆时间&登陆IP
     * @return bool
     * @author 王新龙
     * @date   2019/9/5 11:39 AM
     */
    public function updateLastInfo($id)
    {
        if (empty($id)) {
            return FALSE;
        }
        $oAdmin = (new Admin())->getById($id);
        if (empty($oAdmin)) {
            return FALSE;
        }
        $ip          = Helper::getIp();
        $accessToken = Helper::getRandStr($oAdmin->username);
        $data        = [
            'access_token'    => $accessToken,
            'valid_time'      => date('Y-m-d H:i:s', time() + 259200),
            'last_login_date' => date('Y-m-d H:i:s'),
            'last_login_ip'   => $ip
        ];
        return $oAdmin->updateRecord($data);
    }

    public function updateAccessToken($accessToken)
    {
        if (empty($accessToken)) {
            return FALSE;
        }
        $oAdmin = (new Admin())->getByAccessToken($accessToken);
        if (!empty($oAdmin)) {
            $accessTokenNew = Helper::getRandStr($oAdmin->username);
            $data           = [
                'access_token' => $accessTokenNew,
                'valid_time'   => date('Y-m-d H:i:s', time() + 259200)
            ];
            return $oAdmin->updateRecord($data);
        }
        return FALSE;
    }

    /**
     * 管理员添加逻辑
     *
     * @param $params
     *
     * @return array
     * @throws \yii\base\Exception
     * @throws \yii\db\Exception
     * @author 王新龙
     * @date   2019/12/12 4:20 PM
     */
    public function add($params)
    {
        $fieldArr = ['username', 'role'];
        $result   = Helper::checkRequired($fieldArr, $params);
        if (!$result) {
            return JsonResult::returnArr(Code::$invalid_param);
        }
        $params = ArrayHelper::toArray($params);

        $username = ArrayHelper::getValue($params, 'username', '');
        $password = ArrayHelper::getValue($params, 'password', '');
        $name     = ArrayHelper::getValue($params, 'name', '');
        $mobile   = ArrayHelper::getValue($params, 'mobile', '');
        $role     = ArrayHelper::getValue($params, 'role', '');

        $oAdmin = (new Admin())->getByUsername($username);
        if (!empty($oAdmin)) {
            return JsonResult::returnArr(Code::$admin_is_exist);
        }
        $oAdmin = (new Admin())->getByMobile($mobile);
        if (!empty($oAdmin)) {
            return JsonResult::returnArr(Code::$admin_mobile_is_exist);
        }
        if (empty($role)) {
            return JsonResult::returnArr(Code::$admin_role_not_empty);
        }
        $role = explode(',', $role);

        /**管理员**/
        $data   = [
            'username' => $username,
            'password' => (new Admin())->encryption($password),
            'name'     => $name,
            'mobile'   => $mobile,
            'status'   => Admin::STATUS_NORMAL
        ];
        $mAdmin = new Admin();
        $result = $mAdmin->addRecord($data);
        if (!$result) {
            return JsonResult::returnArr(Code::$add_error, $mAdmin->errinfo);
        }
        /**管理员角色**/
        $data = [];
        $date = date('Y-m-d H:i:s');
        foreach ($role as $item) {
            $data[] = [
                'role_id'    => $item,
                'admin_id'   => $mAdmin->id,
                'created_at' => $date
            ];
        }
        $result = (new AuthAssignment())->insertBatch($data);
        if (!$result) {
            return JsonResult::returnArr(Code::$add_error, $mAdmin->errinfo);
        }
        return JsonResult::returnArr(Code::$ok);
    }

    /**
     * 管理员编辑逻辑
     *
     * @param $params
     *
     * @return array
     * @throws \yii\base\Exception
     * @throws \yii\db\Exception
     * @author 王新龙
     * @date   2019/12/12 4:21 PM
     */
    public function edit($params)
    {
        $fieldArr = ['id', 'role'];
        $result   = Helper::checkRequired($fieldArr, $params);
        if (!$result) {
            return JsonResult::returnArr(Code::$invalid_param);
        }
        $params = ArrayHelper::toArray($params);

        $id       = ArrayHelper::getValue($params, 'id', '');
        $password = ArrayHelper::getValue($params, 'password', '');
        $name     = ArrayHelper::getValue($params, 'name', '');
        $mobile   = ArrayHelper::getValue($params, 'mobile', '');
        $role     = ArrayHelper::getValue($params, 'role', '');

        $role = explode(',', $role);

        $oAdmin = (new Admin())->getById($id);
        if (empty($oAdmin)) {
            return JsonResult::returnArr(Code::$admin_not_exists);
        }
        /**管理员**/
        $data = [
            'name'   => $name,
            'mobile' => $mobile
        ];
        if (!empty($password)) {
            $data['password'] = (new Admin())->encryption($password);
        }
        $result = $oAdmin->updateRecord($data);
        if (!$result) {
            return JsonResult::returnArr(Code::$edit_error, $oAdmin->errinfo);
        }
        /**管理员角色**/
        (new AuthAssignment())->delByAdminId($oAdmin->id);

        $data = [];
        $date = date('Y-m-d H:i:s');
        foreach ($role as $item) {
            $data[] = [
                'role_id'    => $item,
                'admin_id'   => $oAdmin->id,
                'created_at' => $date
            ];
        }
        $result = (new AuthAssignment())->insertBatch($data);
        if (!$result) {
            return JsonResult::returnArr(Code::$edit_error);
        }
        return JsonResult::returnArr(Code::$ok);
    }

    /**
     * 检测访问权限
     *
     * @param $path
     *
     * @return bool
     * @author 王新龙
     * @date   2019/9/11 7:34 PM
     */
    public function checkVisit($path)
    {
        if (empty($path)) {
            return FALSE;
        }
        $adminInfo = Yii::$app->panel->identity;
        if (in_array($path, $this->whiteList())) {
            return TRUE;
        }
        if (empty($adminInfo)) {
            return FALSE;
        }
        if ($adminInfo['username'] == 'admin') {
            return TRUE;
        }
        $adminId = $adminInfo['id'];

        $list = (new AuthAssignment())->listByAdminId($adminId);
        if (empty($list)) {
            return FALSE;
        }
        $roleIds  = ArrayHelper::getColumn($list, 'role_id');
        $roleList = (new AuthItemChild())->listByRoleId($roleIds);
        if (empty($roleList)) {
            return FALSE;
        }
        $roleList = ArrayHelper::getColumn($roleList, 'child');
        if (!in_array($path, $roleList)) {
            return FALSE;
        }
        return TRUE;
    }

    /**
     * 获取管理员名称
     *
     * @param $adminIds
     *
     * @return array
     * @author 王新龙
     * @date   2020/2/20 2:45 PM
     */
    public function listAdminByIds($adminIds)
    {
        if (empty($adminIds)) {
            return [];
        }
        $data = (new \ada\models\ada_cn_admin\Admin())->findAll($adminIds);
        if (empty($data)) {
            return [];
        }
        $list = [];
        foreach ($data as $item) {
            $list[] = [
                'admin_id' => $item['id'],
                'username' => $item['username'],
                'name'     => $item['name']
            ];
        }
        $list = array_column($list, NULL, 'admin_id');
        return $list;
    }

    /**
     * 列表_where条件
     *
     * @param $params
     *
     * @return array
     * @author 王新龙
     * @date   2019/12/11 5:16 PM
     */
    private function getListWhere($params)
    {
        if (empty($params)) {
            return [];
        }
        $username = ArrayHelper::getValue($params, 'username', '');
        $name     = ArrayHelper::getValue($params, 'name', '');
        $status   = ArrayHelper::getValue($params, 'status', 99);

        $where = [];
        if (!empty($username)) {
            $where[] = ['like', 'username', $username];
        }
        if (!empty($name)) {
            $where[] = ['like', 'name', $name];
        }
        if (!empty($status) && $status != 99) {
            $where[] = ['status' => $status];
        }
        if (!empty($where)) {
            array_unshift($where, 'AND');
        }
        return $where;
    }

    /**
     * 列表_数据处理
     *
     * @param $list
     *
     * @return mixed
     * @author 王新龙
     * @date   2019/12/11 5:16 PM
     */
    private function filterList($list)
    {
        if (empty($list)) {
            return $list;
        }
        $adminIds   = ArrayHelper::getColumn($list, 'id', []);
        $AssData    = (new AuthAssignment())->listByAdminId($adminIds);
        $assignment = [];
        if (!empty($AssData)) {
            $AssData = ArrayHelper::toArray($AssData);
            foreach ($AssData as $item) {
                $assignment[$item['admin_id']][] = $item;
            }
        }
        $roleList = [];
        /**获取角色信息**/
        if (!empty($assignment)) {
            $roleIds = [];
            foreach ($assignment as $item) {
                if (empty($item)) {
                    continue;
                }
                $ids     = ArrayHelper::getColumn($item, 'role_id', []);
                $roleIds = array_merge($roleIds, $ids);
            }
            if (!empty($roleIds)) {
                $roleList = (new AuthRole())->listByIds($roleIds);
                if (!empty($roleList)) {
                    $roleList = ArrayHelper::toArray($roleList);
                    $roleList = array_column($roleList, 'name', 'id');
                }
            }
        }
        /**拼装角色**/
        foreach ($list as &$item) {
            if ($item['username'] == Admin::SUPER_ADMIN) {
                $item['role'] = '超级管理员';
                continue;
            }
            if (empty($assignment[$item['id']])) {
                $item['role'] = '';
                continue;
            }
            $role = '';
            foreach ($assignment[$item['id']] as $val) {
                $str = ArrayHelper::getValue($roleList, $val['role_id'], '');
                if (!empty($str)) {
                    $role = $role . ',' . $str;
                }
            }
            $role         = ltrim($role, ",");
            $item['role'] = $role;
        }
        return $list;
    }

    /**
     * 路径白名单，此名单下的路径，不过权限验证
     * @return array
     * @author 王新龙
     * @date   2019/9/12 4:52 PM
     */
    public function whiteList()
    {
        return [
            /**权限后台白名单**/
            'panel/login/index',//登陆页
            'panel/login/login',//登陆
            'panel/login/logout',//登出
            'panel/login/check',//检测登陆状态
            'panel/login/admin-info',//管理员信息
            'panel/error/index',//错误页面
            'panel/api-menu/menu-info',//菜单对外接口
            'panel/api-admin/update-password',//菜单对外接口
            'panel/admin/password',//修改密码页面
            'panel/admin/update-password',//修改密码
//            'panel/index',//首页
//            'panel/index/index',//首页
//            'panel/menu/menu-info',//菜单列表
            /**社交后台白名单**/
            /**社交电商白名单**/
            /**社交客服白名单**/
            /**社交财务白名单**/
        ];
    }

    /**
     * 清除该admin_id下所有登陆状态
     *
     * @param $adminId
     *
     * @return bool
     * @author 王新龙
     * @date   2020/1/3 6:22 PM
     */
    public function delSessionAll($adminId)
    {
        if (empty($adminId)) {
            return FALSE;
        }
        $aa = Yii::$app->redis->keys('malladmin:*');
        foreach ($aa as $item) {
            $info = Redis::get($item);
            $info = $this->unserialize($info);
            if (!$info || empty($info['userinfo'])) {
                continue;
            }
            if ($info['userinfo']['id'] != $adminId) {
                continue;
            }
            Redis::del($item);
        }

        Yii::$app->session->remove('userinfo');
        Yii::$app->panel->logout(FALSE);
        return TRUE;
    }

    private function unserialize($session_data)
    {
        $method = ini_get("session.serialize_handler");
        switch ($method) {
            case "php":
                return $this->unserialize_php($session_data);
                break;
            case "php_binary":
                return $this->unserialize_phpbinary($session_data);
                break;
            default:
                return FALSE;
        }
    }

    private function unserialize_php($session_data)
    {
        $return_data = [];
        $offset      = 0;
        while ($offset < strlen($session_data)) {
            if (!strstr(substr($session_data, $offset), "|")) {
                return FALSE;
            }
            $pos                   = strpos($session_data, "|", $offset);
            $num                   = $pos - $offset;
            $varname               = substr($session_data, $offset, $num);
            $offset                += $num + 1;
            $data                  = unserialize(substr($session_data, $offset));
            $return_data[$varname] = $data;
            $offset                += strlen(serialize($data));
        }
        return $return_data;
    }

    private function unserialize_phpbinary($session_data)
    {
        $return_data = [];
        $offset      = 0;
        while ($offset < strlen($session_data)) {
            $num                   = ord($session_data[$offset]);
            $offset                += 1;
            $varname               = substr($session_data, $offset, $num);
            $offset                += $num;
            $data                  = unserialize(substr($session_data, $offset));
            $return_data[$varname] = $data;
            $offset                += strlen(serialize($data));
        }
        return $return_data;
    }
}