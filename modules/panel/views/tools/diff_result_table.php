<blockquote class="layui-elem-quote">
    文件名： <?=$filename?> 对比结果
    <hr class="layui-bg-red">
</blockquote>
<!-- SERVER [S] -->
<div class="layui-tab-item layui-show">
    <table class="layui-table" lay-skin="table" lay-size="sm">
        <colgroup>
            <col width="50">
            <col width="150">
            <col>
            <col>
        </colgroup>
        <thead>
        <tr>
            <th>Row</th>
            <th>Field Name </th>
            <th>Legacy Value</th>
            <th>New Value</th>
        </tr>
        </thead>
        <tbody>
        <?php if(!empty($list)): ?>
            <?php foreach((array)$list as $row):?>
                <tr>
                    <td><?=$row['rowNum']?></td>
                    <td><?=$row['fieldName']?></td>
                    <td><?=$row['legacyValue']?></td>
                    <td><?=$row['newValue']?></td>
                </tr>
            <?php endforeach;?>
        <?php else:;?>
            <tr>
                <td colspan="4" align="center">无变更记录</td>
            </tr>
        <?php endif;?>
        </tbody>
    </table>
    <div class="clearfix"></div>
</div>
<!-- SERVER [E] -->