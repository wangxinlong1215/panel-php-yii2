<blockquote class="layui-elem-quote">
    <h5>您所在的位置： 权限管理 <i class="layui-icon layui-icon-triangle-r"></i> 接口列表</h5>
</blockquote>
<div class="search">
    <button class="layui-btn" data-type="synchro">自动同步路由</button>
</div>
<fieldset class="layui-elem-field layui-field-title" style="margin-top: 30px;"></fieldset>
<div id="test4" class="demo-transfer"></div>
<script>
    var csrf = '<?= $csrf;?>';
    var use_list = JSON.parse('<?= $use_list;?>');
    var forbidden_list = JSON.parse('<?= $forbidden_list;?>');
    layui.use(['transfer', 'layer', 'util'], function () {
        var transfer = layui.transfer
            , layer = layui.layer
            , util = layui.util;

        //显示搜索框
        transfer.render({
            elem: '#test4'
            , data: use_list
            , value: forbidden_list//初始右侧数据
            , title: ['全选&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;禁用接口列表', '全选&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;生效接口列表']
            , showSearch: true
            , width: 400
            , height: 600
            , id: 'key123' //定义唯一索引
            , onchange: function (obj, index) {
                var status = index == 0 ? 1 : 2;
                var list = []
                obj.forEach(function (key) {
                    console.log(key.value);
                    list.push(key.value)
                });

                $.ajax({
                    type: "POST",
                    url: "/panel/route/update-status",
                    data: {_csrf: csrf, ids: list, status: status},
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
                            layer.msg(data.msg, {
                                offset: '15px'
                                , icon: 2
                                , time: 1000
                            });
                            location.reload();
                        }
                    },
                    error: function () {
                        layer.closeAll();
                        layer.msg('系统错误，请稍后再试');
                    }
                });
            }
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

        $('.search .layui-btn').on('click', function () {
            var type = $(this).data('type');
            active[type] ? active[type].call(this) : '';
        });
    });
</script>