<div class="layui-fluid">
    <div class="layui-row">
        <form class="layui-form">

            <div class="layui-form-item">
                <label for="name" class="layui-form-label"><span class="x-red">*</span>名称</label>
                <div class="layui-input-inline">
                    <input type="text" id="name" name="name" required="" lay-verify="required" autocomplete="off" class="layui-input" placeholder="请输入名称">
                </div>
            </div>

            <div class="layui-form-item">
                <label for="description" class="layui-form-label"><span class="x-red">*</span>描述</label>
                <div class="layui-input-inline">
                    <input type="text" id="description" name="description" required="" lay-verify="required" autocomplete="off" class="layui-input" placeholder="请输入描述">
                </div>
            </div>

            <div class="layui-form-item">
                <label for="submit_add_menu" class="layui-form-label"></label>
                <div class="layui-input-block">
                    <button class="layui-btn" lay-submit lay-filter="submit_add_role">确认添加</button>
                </div>
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

        form.on('submit(submit_add_role)', function (data) {
            data = data.field;
            var name = data.name;
            var description = data.description;

            $.ajax({
                type: "POST",
                url: "/panel/role/add",
                data: {_csrf: csrf, name: name, description: description},
                datatype: "json",
                success: function (data) {
                    data = eval('(' + data + ')');
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