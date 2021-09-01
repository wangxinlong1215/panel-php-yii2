<div class="x-nav">
    <span class="layui-breadcrumb">
        <a href="">您所在的位置： 接口管理</a>
        <a>
          <cite>接口列表</cite>
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
                        路由：
                        <div class="layui-inline layui-show-xs-block">
                            <input type="text" id="name" name="name" autocomplete="off" class="layui-input" value="<?php echo $_GET['name'] ?? ''; ?>">
                        </div>

                        所属系统：
                        <div class="layui-inline layui-show-xs-block">
                            <select name="system" id="system" lay-filter="mySelect">
                                <option value="99">全部</option>
                                <?php foreach ($system_list as $item): ?>
                                    <option value="<?php echo $item['value']; ?>" <?php if (!empty($_GET['system']) && $_GET['system'] == $item['value']): ?>selected="selected"<?php endif; ?>><?php echo $item['system']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        名称填写状态：
                        <div class="layui-inline layui-show-xs-block">
                            <select name="rule_name_status" id="rule_name_status" lay-filter="mySelect">
                                <option value="99" <?php if (!empty($_GET['rule_name_status']) && $_GET['rule_name_status'] == 99): ?>selected="selected"<?php endif; ?>>全部</option>
                                <option value="1" <?php if (!empty($_GET['rule_name_status']) && $_GET['rule_name_status'] == 1): ?>selected="selected"<?php endif; ?>>已填写</option>
                                <option value="2" <?php if (!empty($_GET['rule_name_status']) && $_GET['rule_name_status'] == 2): ?>selected="selected"<?php endif; ?>>未填写</option>
                            </select>
                        </div>

                        <div class="layui-inline layui-show-xs-block">
                            <button class="layui-btn" lay-submit="" lay-filter="sreach"><i class="layui-icon">&#xe615;</i></button>
                        </div>
                    </form>
                </div>

                <div class="search">
                    <button class="layui-btn" data-type="synchro"><i class="layui-icon"></i>同步路由</button>
                </div>

                <div class="layui-card-body layui-table-body layui-table-main">
                    <table class="layui-table layui-form" lay-filter="table">
                        <thead>
                        <tr>
                            <th>路由</th>
                            <th>所属系统</th>
                            <th>状态</th>
                            <th>名称</th>
                            <th>描述</th>
                        </thead>
                        <tbody>
                        <?php foreach ($data as $val): ?>
                            <tr>
                                <td><?php echo $val['name']; ?></td>
                                <td><?php echo $val['system']; ?></td>
                                <td class="td-status">
                                    <input type="checkbox" name="status" value="<?php echo $val['id']; ?>" lay-skin="switch" lay-text="正常|禁用" lay-filter="statusTpl" <?php if ($val['status'] == 1): ?>checked<?php endif; ?>>
                                </td>

                                <td>
                                    <input type="text" class="layui-input x-sort" name="rule_name" value="<?php echo $val['rule_name']; ?>" onchange="updateRuleName(this,<?php echo $val["id"]; ?>)" ">
                                </td>
                                <td><?php echo $val['description']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div id="page_container"></div>
            </div>
        </div>
    </div>
</div>
<script>
    var csrf = '<?= $csrf;?>';
    layui.use(['table', 'laydate', 'layer', 'laypage'], function () {
        var table = layui.table
            , layer = layui.layer
            , laypage = layui.laypage
            , form = layui.form
            , $ = layui.jquery;

        laypage.render({
            elem: 'page_container'
            ,count: <?php echo $count;?>
            ,curr: <?php echo $_GET['page'] ?? 0;?>
            ,hash: 'fenye' //自定义hash值
            ,jump: function(obj, first){
                var name = $('#name').val();
                var system = $('#system').val();
                var rule_name_status = $('#rule_name_status').val();

                if(!first){
                    window.location.href = '/panel/route/list?name='+name+'&system='+system+'&rule_name_status='+rule_name_status+'&page='+obj.curr+'&limit='+obj.limit;
                }
            }
        });

        $('.search .layui-btn').on('click', function () {
            var type = $(this).data('type');
            active[type] ? active[type].call(this) : '';
        });

        var $ = layui.$, active = {
            synchro: function () {
                layer.msg('同步路由中，请勿操作或关闭窗口', {
                    shade: true,
                    time: false
                });

                $.ajax({
                    type: "POST",
                    url: "/panel/route/synchro",
                    data: {_csrf: csrf},
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
                            setTimeout(function () {
                                location.reload();
                            }, 1000)
                        } else {
                            layer.msg(data.msg, {
                                offset: '15px'
                                , icon: 2
                                , time: 1000
                            });
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
            }
        };

        table.on('row(ruleNameTpl)', function (obj) {

            console.log(obj.tr) //得到当前行元素对象
            console.log(obj.data) //得到当前行数据
            alert(1);

            return false;

            var value = obj.value //得到修改后的值
                , data = obj.data //得到所在行所有键值
                , field = obj.field; //得到字段
            var _data = {_csrf: csrf, id: data.id};
            _data[field] = value;
            $.ajax({
                type: "POST",
                url: "/panel/route/update-item",
                data: _data,
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

        form.on('switch(statusTpl)', function (obj) {
            var id = this.value;
            var status = 2;
            if (obj.elem.checked === true) {
                status = 1;
            }
            $.ajax({
                type: "POST",
                url: "/panel/route/update-status",
                data: {_csrf: csrf, status: status, ids: id},
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

    function updateRuleName(obj, id) {
        $.ajax({
            type: "POST",
            url: "/panel/route/update-rule-name",
            data: {_csrf: csrf, id: id, rule_name: $(obj).val()},
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
</script>