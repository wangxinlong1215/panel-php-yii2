<?php

namespace app\common\logger;

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
    /******..模块:10*******/
}