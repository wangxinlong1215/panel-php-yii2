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
                <label for="system" class="layui-form-label"><span class="x-red">*</span>所属系统</label>
                <div class="layui-input-block">
                    <select name="system" xm-select="select1" lay-filter="system">
                        <?php foreach ($system_list as $item): ?>
                            <?php echo $item; ?>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="layui-form-item">
                <label for="parent" class="layui-form-label">父级名称</label>
                <div class="layui-input-block">
                    <select name="parent" xm-select="select1">
                        <option value=""></option>
                        <?php foreach ($menu_list as $val): ?>
                            <option><?php echo $val ?></option>
                        <?php endforeach; ?>
                    </select>
                    </select>
                </div>
            </div>

            <div class="layui-form-item">
                <label for="route" class="layui-form-label">路由</label>
                <div class="layui-input-inline">
                    <input type="text" id="route" name="route" required="" autocomplete="off" class="layui-input" placeholder="请输入路由">
                </div>
            </div>

            <div class="layui-form-item">
                <label for="path" class="layui-form-label">前端路径</label>
                <div class="layui-input-inline">
                    <input type="text" id="path" name="path" required="" autocomplete="off" class="layui-input" placeholder="请输入前端路径">
                </div>
            </div>

            <div class="layui-form-item">
                <label for="icon" class="layui-form-label">图标</label>
                <div class="layui-input-inline">
                    <input type="text" id="icon" name="icon" required="" autocomplete="off" class="layui-input" placeholder="请输入图标">
                </div>
            </div>

            <div class="layui-form-item">
                <label for="order" class="layui-form-label"><span class="x-red">*</span>排序</label>
                <div class="layui-input-inline">
                    <input type="text" id="order" name="order" required="" lay-verify="required" autocomplete="off" class="layui-input" placeholder="请输入排序">
                </div>
            </div>


            <div class="layui-form-item">
                <label for="submit_add_menu" class="layui-form-label"></label>
                <div class="layui-input-block">
                    <button class="layui-btn" lay-submit lay-filter="submit_add_menu">确认添加</button>
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

        form.on('submit(submit_add_menu)', function (data) {
            data = data.field;
            var name = data.name;
            var system = data.system;
            var parent = data.parent;
            var route = data.route;
            var path = data.path;
            var icon = data.icon;
            var order = data.order;

            if (parent.length <= 0) {
                parent = 0;
            }
            if (route.length <= 0) {
                route = '';
            }
            $.ajax({
                type: "POST",
                url: "/panel/menu/add",
                data: {
                    _csrf: csrf,
                    name: name,
                    system: system,
                    parent: parent,
                    route: route,
                    path: path,
                    icon: icon,
                    order: order
                },
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
                        return false;
                    }
                    layer.closeAll();
                    layer.msg(data.msg, {
                        offset: '15px'
                        , icon: 2
                        , time: 1000
                    });
                }
            });
            return false;
        });

        form.on('select(system)', function (data) {
            var system = data.value;
            $.ajax({
                type: "POST",
                url: "/panel/menu/list-parent",
                data: {_csrf: csrf, system: system},
                datatype: "json",
                success: function (data) {
                    data = eval('(' + data + ')');
                    if (data.code == '0000') {
                        var list = data.data;

                        proHtml = '';
                        for (var i = 0; i < list.length; i++) {
                            proHtml += list[i];
                        }
                        $('select[name=parent]').empty();
                        $('select[name=parent]').append(proHtml);
                        form.render();
                    } else {
                        layer.msg('系统错误，请稍后再试');
                        setTimeout(function () {
                            location.reload();
                        }, 1000)
                    }
                },
                error: function () {
                    layer.closeAll();
                    layer.msg('系统错误，请稍后再试');
                    setTimeout(function () {
                        location.reload();
                    }, 1000)
                }
            });
        });
    });
</script>