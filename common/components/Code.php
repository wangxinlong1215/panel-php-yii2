<?php

namespace app\common\components;
class Code
{
    /******系统编码*******/
    public static $ok                    = ['code' => 0, 'msg' => 'ok'];
    public static $success               = ['code' => 200, 'msg' => 'ok'];
    public static $err                   = ['code' => -1, 'msg' => 'error'];
    public static $user_not_register     = ['code' => 200001, 'msg' => '用户未注册'];
    public static $user_not_exists       = ['code' => 200002, 'msg' => '用户不存在'];
    public static $invalid_session       = ['code' => 401, 'msg' => 'Invalid session.'];
    public static $access_error          = ['code' => 403, 'msg' => '权限不足'];
    public static $system_error          = ['code' => 999500, 'msg' => '系统错误'];
    public static $field_error           = ['code' => 999992, 'msg' => '字段错误'];
    public static $add_error             = ['code' => 999993, 'msg' => '添加失败'];
    public static $edit_error            = ['code' => 999994, 'msg' => '更新失败'];
    public static $invalid_param         = ['code' => 999995, 'msg' => '参数非法'];
    public static $data_exception        = ['code' => 999996, 'msg' => '数据异常'];
    public static $invalid_request       = ['code' => 999997, 'msg' => '非法请求'];
    public static $invalid_sign          = ['code' => 999998, 'msg' => '签名无效'];
    public static $internal_server_error = ['code' => 999999, 'msg' => 'internal server error'];
    /******管理模块:10*******/
    public static $admin_not_exists           = ['code' => 100001, 'msg' => '用户不存在'];
    public static $admin_password_err         = ['code' => 100002, 'msg' => '密码错误'];
    public static $admin_invalidation         = ['code' => 100003, 'msg' => '登陆失效'];
    public static $admin_is_blacklist         = ['code' => 100004, 'msg' => '该用户被禁用'];
    public static $admin_is_exist             = ['code' => 100005, 'msg' => '帐号已存在'];
    public static $admin_route_exist          = ['code' => 100006, 'msg' => '路由已存在'];
    public static $admin_menu_exist           = ['code' => 100007, 'msg' => '菜单名称已存在'];
    public static $admin_menu_not_exist       = ['code' => 100008, 'msg' => '菜单不存在'];
    public static $admin_role_exist           = ['code' => 100009, 'msg' => '该角色已存在'];
    public static $admin_role_not_exist       = ['code' => 100010, 'msg' => '该角色不存在'];
    public static $admin_role_not_empty       = ['code' => 100011, 'msg' => '角色不能为空'];
    public static $admin_mobile_is_exist      = ['code' => 100012, 'msg' => '手机号已存在'];
    public static $admin_super_error          = ['code' => 100013, 'msg' => '超级管理员信息不可更改'];
    public static $admin_role_del_error       = ['code' => 100014, 'msg' => '角色已绑定帐号，请先解绑'];
    public static $admin_route_not_exists     = ['code' => 100015, 'msg' => '路由不存在'];
    public static $admin_route_update_err     = ['code' => 100016, 'msg' => '提交内容不能为空'];
    public static $admin_check_pass_err       = ['code' => 100017, 'msg' => '必须是6-20个英文字母、数字或符号(除空格)，且字母、数字和标点符号至少包含两种'];
    public static $admin_check_ole_pass_err   = ['code' => 100018, 'msg' => '输入原密码不正确，请重新输入'];
    public static $admin_check_alike_pass_err = ['code' => 100019, 'msg' => '新密码与原密码不能相同，请重新输入'];
    public static $admin_check_init_pass_err  = ['code' => 100020, 'msg' => '您的密码为初始密码，请修改密码'];
}