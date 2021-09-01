<div class="x-nav">
    <span class="layui-breadcrumb">
        <a href="">您所在的位置： 权限管理</a>
        <a>
          <cite>角色列表</cite>
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
                        名称：
                        <div class="layui-inline layui-show-xs-block">
                            <input type="text" id="name" name="name" autocomplete="off" class="layui-input" value="<?php echo $_GET['name'] ?? '';?>">
                        </div>

                        <div class="layui-inline layui-show-xs-block">
                            <button class="layui-btn"  lay-submit="" lay-filter="sreach"><i class="layui-icon">&#xe615;</i></button>
                        </div>
                    </form>
                </div>

                <div class="layui-card-header">
                    <button class="layui-btn" onclick="xadmin.open('添加角色','/panel/role/add',450,250)"><i class="layui-icon"></i>添加角色</button>
                </div>

                <div class="layui-card-body layui-table-body layui-table-main">
                    <table class="layui-table layui-form">
                        <thead>
                        <tr>
                            <th>名称</th>
                            <th>描述</th>
                            <th>操作</th>
                        </thead>
                        <tbody>
                        <?php foreach ($data as $val): ?>
                            <tr>
                                <td><?php echo $val['name'];?></td>
                                <td><?php echo $val['description'];?></td>
                                <td class="td-manage">
                                    <button class="layui-btn layui-btn-warm layui-btn-xs"  onclick="xadmin.open('分配权限','/panel/role/show?id=<?php echo $val["id"];?>')" ><i class="layui-icon">&#xe642;</i>分配权限</button>
                                    <button class="layui-btn layui-btn layui-btn-xs"  onclick="xadmin.open('编辑','/panel/role/edit?id=<?php echo $val["id"];?>',450,250)" ><i class="layui-icon">&#xe642;</i>编辑</button>
                                    <button class="layui-btn-danger layui-btn layui-btn-xs"  onclick="member_del(this,'<?php echo $val["id"];?>')" href="javascript:;" ><i class="layui-icon">&#xe640;</i>删除</button>
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
    layui.use(['table', 'laydate'], function () {
        var table = layui.table
            , laydate = layui.laydate
            , form = layui.form;

        $('.search .layui-btn').on('click', function () {
            var type = $(this).data('type');
            active[type] ? active[type].call(this) : '';
        });
    });

    function member_del(obj,id){
        layer.confirm('确认要删除吗？',function(index){
            $.ajax({
                type: "POST",
                url: "/panel/role/del",
                data: {_csrf: csrf, id: id},
                datatype: "json",
                success: function (data) {
                    data = eval('(' + data + ')');
                    if (data.code == '0000') {
                        $(obj).parents("tr").remove();
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
            layer.close(index);
        });
    }
</script>