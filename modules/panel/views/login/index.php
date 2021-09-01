<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <title>登录页</title>
    <link rel="stylesheet" href="/panel/css/index.css?v=1">
    <script type="text/javascript" src="/panel/js/jquery.min.js"></script>
</head>

<body>
<div class="container">
    <div class="main">
        <div class="title">尚科-权限管理系统</div>
        <div class="pic"></div>
    </div>
    <div class="content">
        <div class="top">
            <div class="header">账户登录</div>
        </div>
        <div class="input layui-form">
            <input type="hidden" name="_csrf" value="<?php echo $csrf; ?>">
            <div class="blank"></div>
            <div class="item">
                <span class="prefix user"></span>
                <input type="text" name="username" id="LAY-user-login-username" lay-verify="required" placeholder="用户名"
                       class="layui-input">
            </div>
            <div class="item">
                <span class="prefix pass"></span>
                <input type="password" name="password" id="LAY-user-login-password" lay-verify="required"
                       placeholder="密码" class="layui-input">
            </div>
            <div class="submit" lay-submit lay-filter="LAY-user-login-submit">登录</div>
            <div class="forget">忘记密码</div>
        </div>
    </div>
    <div class="footer">copyright©2019新浪尚科产品技术</div>
</div>
<script src="/static/layui/layui.js"></script>
<script>
    layui.use(['form'], function () {
        var form = layui.form
            , layer = layui.layer;

        //提交
        form.on('submit(LAY-user-login-submit)', function (obj) {
            $.ajax({
                type: "POST",
                url: "/panel/login/login",
                data: obj.field,
                datatype: "json",
                success: function (data) {
                    if (data.code == 0) {
                        layer.closeAll();
                        layer.msg('登陆成功', {
                            offset: 'auto'
                            , icon: 1
                            , time: 1000
                        });
                        window.location.href = '/panel/index';
                    } else {
                        layer.closeAll();
                        layer.msg(data.msg, {
                            offset: 'auto'
                            , icon: 2
                            , time: 1000
                        });
                    }
                },
                error: function () {
                    layer.closeAll();
                    layer.msg('系统错误，请稍后再试', {
                        offset: 'auto'
                        , icon: 2
                        , time: 1000
                    });
                }
            });
            return false;
        });

        $('.forget').on('click', function () {
            layer.open({
                title: '请联系产品技术重置密码'
                , content: '发邮件至lipengfei@ada.sina.com.cn'
                , btn: ['知道了']
                , icon: 7
            });
        });

        $('#LAY-user-login-password').bind('keyup', function(event) {
            if (event.keyCode == "13") {
                //回车执行查询
                $('.submit').click();
            }
        });
    });
</script>
</body>
</html>