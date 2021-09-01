<div class="layui-fluid">
    <div class="layui-row">
        <form method="post" class="layui-form layui-form-pane">
            <div class="layui-form-item">
                <label for="name" class="layui-form-label">
                    <span class="x-red">*</span>角色名
                </label>
                <div class="layui-input-inline">
                    <input type="text" id="name" name="name" required="" lay-verify="required" autocomplete="off"
                           class="layui-input" value="<?php echo $role_info['name']; ?>">
                </div>
            </div>

            <div class="layui-form-item layui-form-text">
                <label for="description" class="layui-form-label">
                    <span class="x-red">*</span>描述
                </label>
                <div class="layui-input-block">
                    <textarea placeholder="请输入内容" id="description" name="description"
                              class="layui-textarea"><?php echo $role_info['description']; ?></textarea>
                </div>
            </div>

            <div class="layui-form-item layui-form-text">
                <label class="layui-form-label">
                    拥有权限
                </label>
                <table class="layui-table layui-input-block">
                    <tbody>
                    <?php foreach ($role_list as $val): ?>
                        <tr>
                            <td>
                                <input name="id" lay-skin="primary" type="checkbox"
                                       value="<?php echo $val['base']['name']; ?>"
                                       title="<?php echo $val['base']['rule_name']; ?>"
                                       <?php if ($val['base']['is_use'] == 1): ?>checked="checked"<?php endif; ?>
                                       lay-filter="father">
                            </td>
                            <?php if (!empty($val['list'])): ?>
                                <td>
                                    <div class="layui-input-block">
                                        <?php foreach ($val['list'] as $v): ?>
                                            <input name="id" class="son" lay-skin="primary" type="checkbox"
                                                   value="<?php echo $v['name']; ?>"
                                                   title="<?php echo $v['rule_name']; ?>"
                                                   <?php if ($v['is_use'] == 1): ?>checked="checked"<?php endif; ?>
                                                   lay-filter="son">
                                        <?php endforeach; ?>
                                    </div>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="layui-form-item">
                <button class="layui-btn" type="button" onclick="return change();">确认修改</button>
            </div>
        </form>
    </div>
</div>

<script>
    var csrf = '<?= $csrf;?>';
    var role_id = '<?= $role_info['id']?>';

    $('.son').each(function () {
        if (!$(this).attr('checked')) {
            $(this).parent().parent().parent().find('td').eq(0).find('input').prop("checked", false);
        }
    });

    function change() {
        var ids = [];
        $('input[name="id"]:checked').each(function (index, value) {
            ids.push($(this).val());
        });
        console.log(ids);

        $.ajax({
            type: "POST",
            url: "/panel/role/add-role-child",
            data: {_csrf: csrf, role_id: role_id, child: ids},
            datatype: "json",
            success: function (data) {
                if (data.code == '0000') {
                    layer.msg('操作成功', {offset: '100px', icon: 1, time: 1000}, function () {
                        var index = parent.layer.getFrameIndex(window.name);
                        parent.layer.close(index);
                    });
                    return false;
                }

                layer.msg(data.msg, {offset: '100px', icon: 2, time: 1000});
                location.reload();
            }
        });
        return false;
    }

    layui.use(['form', 'layer'], function () {
        $ = layui.jquery;
        var form = layui.form
            , layer = layui.layer;

        form.on('checkbox(father)', function (data) {
            if (data.elem.checked) {
                $(data.elem).parent().siblings('td').find('input').prop("checked", true);
                form.render();
            } else {
                $(data.elem).parent().siblings('td').find('input').prop("checked", false);
                form.render();
            }
        });

        form.on('checkbox(son)', function (data) {
            if (data.elem.checked) {
                var len = $(data.elem).parent().find('input').length;
                var checked_len = 0;
                $(data.elem).parent().find('input').each(function () {
                    if ($(this).is(':checked')) {
                        checked_len += 1;
                    }
                });
                if (len == checked_len) {
                    $(data.elem).parent().parent().parent().find('td').eq(0).find('input').prop("checked", true);
                }
                form.render();
            } else {
                $(data.elem).parent().parent().parent().find('td').eq(0).find('input').prop("checked", false);
                form.render();
            }
        });

        $('.search .layui-btn').on('click', function () {
            var type = $(this).data('type');
            active[type] ? active[type].call(this) : '';
        });
    });
</script>