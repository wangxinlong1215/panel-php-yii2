<div class="x-nav">
    <span class="layui-breadcrumb">
        <a href="">您所在的位置： 权限管理</a>
        <a>
          <cite>菜单列表</cite>
        </a>
    </span>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right"
       onclick="location.reload()" title="刷新">
        <i class="layui-icon layui-icon-refresh" style="line-height:30px"></i>
    </a>
</div>

<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-body ">
                    <form class="layui-form layui-col-space5">
                        所属系统：
                        <div class="layui-inline layui-show-xs-block">
                            <select name="system" id="system" lay-filter="mySelect">
                                <?php foreach ($system_list as $item): ?>
                                    <option value="<?php echo $item['value']; ?>"
                                            <?php if (!empty($_GET['system']) && $_GET['system'] == $item['value']): ?>selected="selected"<?php endif; ?>><?php echo $item['system']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="layui-inline layui-show-xs-block">
                            <button class="layui-btn" lay-submit="" lay-filter="sreach"><i
                                        class="layui-icon">&#xe615;</i></button>
                        </div>
                    </form>
                </div>

                <div class="layui-card-body">
                    <button class="layui-btn" onclick="xadmin.open('添加菜单','/panel/menu/add',600,600)"><i
                                class="layui-icon"></i>添加菜单
                    </button>
                </div>

                <div class="layui-card-body ">
                    <table class="layui-table layui-form">
                        <thead>
                        <tr>
                            <th>名称</th>
                            <th>父级</th>
                            <th>路由</th>
                            <th>前端路径</th>
                            <th>图标</th>
                            <th>所属系统</th>
                            <th width="50">排序</th>
                            <th width="250">操作</th>
                        </thead>
                        <tbody class="x-cate">
                        <?php foreach ($data as $val): ?>
                            <tr cate-id='<?php echo $val['id']; ?>' fid='<?php echo $val['parent']; ?>' <?php if ($val['parent'] == 0): ?>style="background-color: #e2e2e2"<?php endif; ?>>
                                <td>
                                    <?php if ($val['parent'] == 0): ?>
                                        <i class="layui-icon x-show" status='true'>&#xe623;</i>
                                    <?php else: ?>
                                        &nbsp;&nbsp;&nbsp;&nbsp;├
                                    <?php endif; ?>
                                    <?php echo $val['name']; ?>
                                </td>
                                <td><?php echo $val['parent_name']; ?></td>
                                <td><?php echo $val['route']; ?></td>
                                <td><?php echo $val['path']; ?></td>
                                <td><?php echo $val['icon']; ?></td>
                                <td><?php echo $val['system']; ?></td>
                                <td><input type="text" class="layui-input x-sort" name="order"
                                           value="<?php echo $val['order']; ?>"
                                           onchange="updateOrder(this,<?php echo $val["id"]; ?>)">
                                </td>
                                <td class="td-manage">
                                    <button class="layui-btn layui-btn layui-btn-xs"
                                            onclick="xadmin.open('编辑','/panel/menu/edit?id=<?php echo $val["id"]; ?>',600,600)">
                                        <i class="layui-icon">&#xe642;</i>编辑
                                    </button>
                                    <?php if ($val['parent'] == 0): ?>
                                        <button class="layui-btn layui-btn-warm layui-btn-xs"
                                                onclick="xadmin.open('添加菜单','/panel/menu/add?id=<?php echo $val["id"]; ?>',600,600)">
                                            <i class="layui-icon">&#xe642;</i>添加子栏目
                                        </button>
                                    <?php endif; ?>
                                    <button class="layui-btn-danger layui-btn layui-btn-xs"
                                            onclick="member_del(this,'<?php echo $val["id"]; ?>')" href="javascript:;">
                                        <i class="layui-icon">&#xe640;</i>删除
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var csrf = '<?= $csrf;?>';
    layui.use(['table', 'laydate'], function () {
        var table = layui.table
            , laydate = layui.laydate
            , form = layui.form;

        $('.search .layui-btn').on('click', function () {
            var type = $(this).data('type');
            active[type] ? active[type].call(this) : '';
        });

        form.on('switch(statusTpl)', function (obj) {
            var id = this.value;
            var status = 2;
            if (obj.elem.checked === true) {
                status = 1;
            }
            $.ajax({
                type: "POST",
                url: "/panel/admin/update-status",
                data: {_csrf: csrf, status: status, id: id},
                datatype: "json",
                success: function (data) {
                    data = eval('(' + data + ')');
                    if (data.code == '0000') {
                        layer.closeAll();
                        layer.msg('操作成功', {
                            offset: '15px'
                            , icon: 1
                            , time: 1000
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
        });
    });

    function updateOrder(obj, id) {
        $.ajax({
            type: "POST",
            url: "/panel/menu/update-order",
            data: {_csrf: csrf, id: id, order: $(obj).val()},
            datatype: "json",
            success: function (data) {
                if (data.code == '0000') {
                    layer.msg('操作成功', {offset: '100px', icon: 1, time: 1000});
                    return false;
                }
                layer.closeAll();
                layer.msg(data.msg, {offset: '100px', icon: 2, time: 1000});
            },
            error: function () {
                layer.closeAll();
                layer.msg('系统错误，请稍后再试');
            }
        });
    }

    $(function () {
        $("tbody.x-cate tr[fid!='0']").hide();
        // 栏目多级显示效果
        $('.x-show').click(function () {
            if ($(this).attr('status') == 'true') {
                $(this).html('&#xe625;');
                $(this).attr('status', 'false');
                cateId = $(this).parents('tr').attr('cate-id');
                $("tbody tr[fid=" + cateId + "]").show();
            } else {
                cateIds = [];
                $(this).html('&#xe623;');
                $(this).attr('status', 'true');
                cateId = $(this).parents('tr').attr('cate-id');
                getCateId(cateId);
                for (var i in cateIds) {
                    $("tbody tr[cate-id=" + cateIds[i] + "]").hide().find('.x-show').html('&#xe623;').attr('status', 'true');
                }
            }
        })
    })

    var cateIds = [];

    function getCateId(cateId) {
        $("tbody tr[fid=" + cateId + "]").each(function (index, el) {
            id = $(el).attr('cate-id');
            cateIds.push(id);
            getCateId(id);
        });
    }

    function member_del(obj, id) {
        layer.confirm('确认要删除吗？', function (index) {
            $.ajax({
                type: "POST",
                url: "/panel/menu/del",
                data: {_csrf: csrf, id: id},
                datatype: "json",
                success: function (data) {
                    data = eval('(' + data + ')');
                    if (data.code == '0000') {
                        $(obj).parents("tr").remove();
                        layer.closeAll();
                        layer.msg('操作成功', {icon: 1, time: 1000});
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
        });
    }
</script>