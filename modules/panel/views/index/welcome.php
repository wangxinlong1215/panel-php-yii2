<div>
    <div style="text-align:center; padding:48px 0;">欢迎使用后台管理系统，有任何问题请及时向开发人员反馈！</div>
</div>
<script>
    $('tr').each(function () {
        var aa = $(this).find('input');
        if (aa.length < 4) {
            return;
        }
        var input_type = aa.eq(0).attr('type');
        console.log(input_type);
        if (input_type == 'radio') {
            var rand = parseInt(Math.random() * (3 - 0 + 1) + 0);
            aa.eq(rand).attr('checked', true);
        }
        if (input_type == 'checkbox') {
            aa.each(function () {
                var rand = parseInt(Math.random() * (2 - 0 + 1) + 0);
                if (rand == 0 || rand == 1) {
                    $(this).attr('checked', true);
                }
            });
        }
    });
</script>