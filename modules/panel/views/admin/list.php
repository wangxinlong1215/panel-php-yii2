<div class="x-nav">
    <span class="layui-breadcrumb">
        <a href="">您所在的位置： 权限管理</a>
        <a>
          <cite>管理员列表</cite>
        </a>
    </span>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" onclick="location.reload()" title="刷新">
        <i class="layui-icon layui-icon-refresh" style="line-height:30px"></i>
    </a>
</div>

<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-body ">
                    <form class="layui-form layui-col-space5">
                        用户名：
                        <div class="layui-inline layui-show-xs-block">
                            <input type="text" id="username" name="username" autocomplete="off" class="layui-input" value="<?php echo $_GET['username'] ?? '';?>">
                        </div>

                        真实姓名：
                        <div class="layui-inline layui-show-xs-block">
                            <input type="text" id="name" name="name" autocomplete="off" class="layui-input" value="<?php echo $_GET['name'] ?? '';?>">
                        </div>

                        状态：
                        <div class="layui-inline layui-show-xs-block">
                            <select name="status" id="status" lay-filter="mySelect">
                                <option value="99" <?php if (!empty($_GET['status']) && $_GET['status'] == 99): ?>selected="selected"<?php endif; ?>>全部</option>
                                <option value="1"  <?php if (!empty($_GET['status']) && $_GET['status'] == 1): ?>selected="selected"<?php endif; ?>>正常</option>
                                <option value="2"  <?php if (!empty($_GET['status']) && $_GET['status'] == 2): ?>selected="selected"<?php endif; ?>>禁用</option>
                            </select>
                        </div>

                        <div class="layui-inline layui-show-xs-block">
                            <button class="layui-btn"  lay-submit="" lay-filter="sreach"><i class="layui-icon">&#xe615;</i></button>
                        </div>
                    </form>
                </div>

                <div class="layui-card-header">
                    <button class="layui-btn" onclick="xadmin.open('添加用户','/panel/admin/add',600,400)"><i class="layui-icon"></i>添加管理员</button>
                </div>

                <div class="layui-card-body layui-table-body layui-table-main">
                    <table class="layui-table layui-form">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>登录帐号</th>
                            <th>真实姓名</th>
                            <th>手机号</th>
                            <th>角色</th>
                            <th>最后登录时间</th>
                            <th>最后登录IP</th>
                            <th>状态</th>
                            <th>操作</th>
                        </thead>
                        <tbody>
                        <?php foreach ($data as $val): ?>
                            <tr>
                                <td><?php echo $val['id'];?></td>
                                <td><?php echo $val['username'];?></td>
                                <td><?php echo $val['name'];?></td>
                                <td><?php echo $val['mobile'];?></td>
                                <td><?php echo $val['role'];?></td>
                                <td><?php echo $val['last_login_date'];?></td>
                                <td><?php echo $val['last_login_ip'];?></td>
                                <td class="td-status">
                                    <input type="checkbox" name="status" value="<?php echo $val['id'];?>" lay-skin="switch" lay-text="正常|禁用" lay-filter="statusTpl" <?php if ($val['status'] == 1): ?>checked<?php endif; ?>>
                                </td>
                                <td class="td-manage">
                                    <button class="layui-btn layui-btn layui-btn-xs"  onclick="xadmin.open('编辑','/panel/admin/edit?id='+<?php echo $val['id'];?>,600,400)" ><i class="layui-icon">&#xe642;</i>编辑</button>
                                </td>
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
    layui.use(['table', 'laydate','laypage'], function () {
        var table = layui.table
            , laydate = layui.laydate
            , laypage = layui.laypage
            , form = layui.form;

        laypage.render({
            elem: 'page_container'
            ,count: <?php echo $count;?>
            ,curr: <?php echo $_GET['page'] ?? 0;?>
            ,hash: 'fenye' //自定义hash值
            ,jump: function(obj, first){
                var username = $('#username').val();
                var name = $('#name').val();
                var status = $("#status option:selected").val();

                if(!first){
                    window.location.href = '/panel/admin/list?username='+username+'&name='+name+'&status='+status+'&page='+obj.curr+'&limit='+obj.limit;
                }
            }
        });

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
</script>