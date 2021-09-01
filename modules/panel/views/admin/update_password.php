<div class="layui-fluid">
    <div class="layui-row">
        <form class="layui-form">

            <div class="layui-form-item">
                <label for="username" class="layui-form-label">
                    <span class="x-red">*</span>当前密码
                </label>
                <div class="layui-input-inline">
                    <input type="text" id="current_password" name="current_password" required="" lay-verify="required" autocomplete="off" class="layui-input">
                </div>
            </div>

            <div class="layui-form-item">
                <label for="phone" class="layui-form-label">
                    <span class="x-red">*</span>新密码
                </label>
                <div class="layui-input-inline">
                    <input type="text" id="password" name="password" required="" lay-verify="pass" autocomplete="off" class="layui-input">
                </div>
                <div class="layui-form-mid layui-word-aux">
                    <span class="x-red">*</span>密码必须包含大写字母、小写字母、数字、特殊符号的不少于6位的复杂密码
                </div>
            </div>

            <div class="layui-form-item">
                <label for="L_email" class="layui-form-label">
                    <span class="x-red">*</span>确认新密码
                </label>
                <div class="layui-input-inline">
                    <input type="text" id="repassword" name="repassword" required="" lay-verify="repass" autocomplete="off" class="layui-input">
                </div>
            </div>

            <div class="layui-form-item">
                <label for="L_repass" class="layui-form-label">
                </label>
                <button class="layui-btn" lay-filter="submit_update_password" lay-submit="">
                    确认修改
                </button>
            </div>

        </form>
    </div>
</div>
<script>
    var csrf = '<?= $csrf;?>';
    layui.use(['form', 'layedit', 'laydate'], function () {
        var form = layui.form
            , layer = layui.layer
            , layedit = layui.layedit
            , laydate = layui.laydate;

        form.on('submit(submit_update_password)', function (data) {
            data = data.field;
            var current_password = data.current_password;
            var password = data.password;
            var repassword = data.repassword;

            if (password.length < 6 || password.length > 16) {
                layer.msg('密码长度应为6到16个字符', {
                    offset: '15px'
                    , icon: 2
                    , time: 1000
                });
                return false;
            }

            $.ajax({
                type: "POST",
                url: "/panel/admin/update-password",
                data: {_csrf: csrf, current_password: current_password, password: password, repassword: repassword},
                datatype: "json",
                success: function (data) {
                    if (data.code == '0000') {
                        layer.closeAll();
                        layer.msg('操作成功', {
                            offset: '15px'
                            , icon: 1
                            , time: 1000
                        },function () {
                            xadmin.close();
                            xadmin.father_reload();
                        });
                    } else {
                        layer.closeAll();
                        layer.msg(data.msg, {
                            offset: '15px'
                            , icon: 2
                            , time: 1000
                        });
                    }
                },
                error: function () {
                    layer.closeAll();
                    layer.msg('系统错误，请稍后再试');
                }
            });
            return false;
        });
    });
</script>


