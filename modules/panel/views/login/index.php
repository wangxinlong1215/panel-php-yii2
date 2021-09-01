<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>后台管理系统</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <link rel="stylesheet" href="/static/panel/layui/css/layui.css" media="all">
    <link rel="stylesheet" href="/static/panel/style/admin.css" media="all">
    <link rel="stylesheet" href="/static/panel/style/login.css" media="all">
</head>
<body>
<div class="layadmin-user-login layadmin-user-display-show" id="LAY-user-login" style="display: none;">
    <div class="layadmin-user-login-main">
        <div class="layadmin-user-login-box layadmin-user-login-header">
            <h2>后台管理系统</h2>
            <p></p>
        </div>
        <div class="layadmin-user-login-box layadmin-user-login-body layui-form">
            <input type="hidden" name="_csrf" value="<?php echo $csrf; ?>">
            <div class="layui-form-item">
                <label class="layadmin-user-login-icon layui-icon layui-icon-username" for="LAY-user-login-username"></label>
                <input type="text" name="username" id="LAY-user-login-username" lay-verify="required" placeholder="用户名" class="layui-input">
            </div>
            <div class="layui-form-item">
                <label class="layadmin-user-login-icon layui-icon layui-icon-password" for="LAY-user-login-password"></label>
                <input type="password" name="password" id="LAY-user-login-password" lay-verify="required" placeholder="密码" class="layui-input">
            </div>
            <div class="layui-form-item" style="margin-bottom: 20px;">
                <input type="checkbox" name="remember" lay-skin="primary" title="记住密码">
                <a href="javascript:void(0);" class="layadmin-user-jump-change layadmin-link forget" style="margin-top: 7px;">忘记密码？</a>
            </div>
            <div class="layui-form-item">
                <button class="layui-btn layui-btn-fluid login-submit" lay-submit lay-filter="LAY-user-login-submit">登 入</button>
            </div>
        </div>
    </div>
    <div class="layui-trans layadmin-user-login-footer">
        <!--        <p>© 2021 <a href="https://github.com/" target="_blank">github</a></p>-->
    </div>
</div>
<script src="/static/panel/layui/layui.js"></script>
<script>
    layui.config({
        base: '/static/panel/'
        , version: Date.parse(new Date())
    }).extend({
        index: 'lib/index' //主入口模块
    }).use(['index', 'user'], function () {
        var $ = layui.$
            , setter = layui.setter
            , admin = layui.admin
            , form = layui.form
            , router = layui.router()
            , search = router.search;

        form.render();

        form.on('submit(LAY-user-login-submit)', function (obj) {
            admin.req({
                type: "POST"
                , url: '/panel/login/login'
                , data: obj.field
                // , datatype: "json"
                , done: function (res) {
                    // if (res.code == 0) {
                    //     layer.closeAll();
                    //     layer.msg('登陆成功', {
                    //         offset: 'auto'
                    //         , icon: 1
                    //         , time: 1000
                    //     });
                    //     window.location.href = '/panel/index';
                    // } else {
                    //     layer.closeAll();
                    //     layer.msg(data.msg, {
                    //         offset: 'auto'
                    //         , icon: 2
                    //         , time: 1000
                    //     });
                    // }

                    //请求成功后，写入 access_token
                    // layui.data(setter.tableName, {
                    //     key: setter.request.tokenName
                    //     , value: res.data.access_token
                    // });

                    //登入成功的提示与跳转
                    // layer.msg('登入成功', {
                    //     offset: '15px'
                    //     , icon: 1
                    //     , time: 1000
                    // }, function () {
                    //     location.href = '../'; //后台主页
                    // });
                }
            });
        });

        //忘记密码
        $('.forget').on('click', function () {
            layer.open({
                title: '请联系产品技术重置密码'
                , content: '发邮件至xxx'
                , btn: ['知道了']
                , icon: 7
            });
        });

        //光标在密码输入框，回车事件
        $('#LAY-user-login-password').bind('keyup', function (event) {
            if (event.keyCode == "13") {
                $('.login-submit').click();
            }
        });
    });
</script>
</body>
</html>