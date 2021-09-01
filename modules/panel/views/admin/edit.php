<link rel="stylesheet" href="/static/layui/extend/formSelects-v4.css"/>
<div class="layui-fluid">
    <div class="layui-row">
        <form class="layui-form">

            <div class="layui-form-item">
                <label for="username" class="layui-form-label"><span class="x-red">*</span>登录名</label>
                <div class="layui-input-inline">
                    <input type="text" id="username" name="username" readonly="readonly" lay-verify="required" autocomplete="off" class="layui-input" placeholder="请输入用户名" value="<?php echo $username; ?>">
                </div>
                <div class="layui-form-mid layui-word-aux">
                    <span class="x-red">*</span>将会成为您唯一的登入名
                </div>
            </div>

            <div class="layui-form-item">
                <label for="phone" class="layui-form-label">密码</label>
                <div class="layui-input-inline">
                    <input type="text" id="password" name="password" required="" autocomplete="off" class="layui-input" placeholder="请输入密码">
                </div>
                <div class="layui-form-mid layui-word-aux">
                    <span class="x-red">*</span>无修改可不填写
                </div>
            </div>

            <div class="layui-form-item">
                <label for="username" class="layui-form-label"><span class="x-red">*</span>姓名</label>
                <div class="layui-input-inline">
                    <input type="text" id="name" name="name" required="" lay-verify="required" autocomplete="off" class="layui-input" placeholder="请输入姓名" value="<?php echo $name; ?>">
                </div>
            </div>


            <div class="layui-form-item">
                <label for="username" class="layui-form-label">手机号</label>
                <div class="layui-input-inline">
                    <input type="text" id="mobile" name="mobile" required="" autocomplete="off" class="layui-input" placeholder="请输入手机号" value="<?php echo $mobile; ?>">
                </div>
            </div>

            <div class="layui-form-item">
                <label for="username" class="layui-form-label"><span class="x-red">*</span>角色</label>
                <div class="layui-input-block">
                    <select name="role" xm-select="select1">
                        <?php foreach ($role_list as $val): ?>
                            <option value="<?php echo $val['id']; ?>" <?php if ($val['is_select'] == 1): ?>selected="selected"<?php endif; ?>><?php echo $val['name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <input type="hidden" id="id" name="id" value="<?php echo $id; ?>">

            <div class="layui-form-item">
                <label for="L_repass" class="layui-form-label"></label>
                <div class="layui-input-block">
                    <button class="layui-btn" lay-submit="" lay-filter="submit_add_admin">确认添加</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    var csrf = '<?= $csrf;?>';

    layui.config({
        base: '/static/layui/extend/' //此处路径请自行处理, 可以使用绝对路径
    }).extend({
        formSelects: 'formSelects-v4'
    });
    layui.use(['form', 'layedit', 'laydate', 'jquery', 'formSelects'], function () {
        var form = layui.form
            , layer = layui.layer
            , layedit = layui.layedit
            , laydate = layui.laydate
            , formSelects = layui.formSelects;

        form.on('submit(submit_add_admin)', function (data) {
            data = data.field;
            var id = data.id;
            var password = data.password;
            var name = data.name;
            var mobile = data.mobile;
            var role = data.role;

            $.ajax({
                type: "POST",
                url: "/panel/admin/edit",
                data: {
                    _csrf: csrf,
                    id: id,
                    password: password,
                    name: name,
                    mobile: mobile,
                    role: role
                },
                datatype: "json",
                success: function (data) {
                    if (data.code == '0000') {
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