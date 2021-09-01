<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<title><?php echo $title ?></title>
	<link rel="stylesheet" href="/static/layui/css/layui.css">
	<script src="/static/layui/layui.js"></script>
</head>
<body>
<blockquote class="layui-elem-quote">
	<h5>您所在的位置： 工具集合 <i class="layui-icon layui-icon-triangle-r"></i> <?php echo $title?></h5>
</blockquote>
<blockquote class="layui-elem-quote layui-quote-nm">
	<span class="layui-badge-dot"></span>
	<span class="layui-badge-dot layui-bg-orange"></span>
	<span class="layui-badge-dot layui-bg-green"></span>
	<span class="layui-badge-dot layui-bg-cyan"></span>
	<span class="layui-badge-dot layui-bg-blue"></span>
	<span class="layui-badge-dot layui-bg-gray"></span>
	<span class="layui-badge-dot layui-bg-black"></span>
	<span class="layui-badge-dot layui-bg-orange"></span>
	<span class="layui-badge">功能说明</span>
	<hr class="layui-bg-red">
	<ul class="site-dir layui-layer-wrap"><li>本功能是将多个Excel导入系统，系统会生成一个full.zip文件上传至s3服务器，并返回其下载链接</li></ul>
</blockquote>
<fieldset class="layui-elem-field">
	<legend>请选择一个或多个Excel文件用以生成full.zip</legend>
	<div class="layui-field-box">
<!--            <div class="layui-form-item">-->
<!--                <label class="layui-form-label">版本描述：</label>-->
<!--                <div class="layui-input-block">-->
<!--                    <input type="text" name="description" required  lay-verify="required" placeholder="版本描述" autocomplete="off" class="layui-input">-->
<!--                </div>-->
<!--            </div>-->
            <div class="layui-form-item">
                <label class="layui-form-label">内容类型</label>
                <div class="layui-input-block">
                    <select name="dataType" lay-verify="required" class="layui-input">
                        <option value="">请选择</option>
                        <option value="SERVER">SERVER</option>
                        <option value="CLIENT">CLIENT</option>
                    </select>
                </div>
            </div>
			<div class="layui-form-item">
				<label class="layui-form-label">选择文件：</label>
				<button type="button" class="layui-btn" id="upfile">
					<i class="layui-icon" style="color: #00FFFF;">&#xe60a;</i>点击选择要上传的Excel文件
				</button>
			</div>

			<div class="layui-form-item">
				<label class="layui-form-label">下载地址：</label>
                <div class="layui-form-mid layui-word-aux" id="url_addr">生成full.zip文件后这里会显示full.zip文件的下载地址</div>
			</div>

<!--            <div class="layui-form-item">-->
<!--                <div class="layui-progress layui-progress-big" lay-filter="up_progress" lay-showPercent="true">-->
<!--                    <div class="layui-progress-bar" lay-percent="0%"></div>-->
<!--                </div>-->
<!--            </div>-->

			<div class="layui-form-item">
				<div class="layui-input-block">
					<button class="layui-btn" id="btn_up">开始上传</button>
				</div>
			</div>
	</div>
</fieldset>

<script>
    layui.use(['upload', 'element'], function(){
        var layer = layui.layer,
            $ = layui.jquery,
            upload = layui.upload,
            element = layui.element;
        var loading;

        //执行实例
        var uploadInst = upload.render({
            elem: '#upfile' //绑定元素
            ,url: '/operation/fullzip/upload-excel'
            ,accept: 'file' //普通文件
            ,auto: false   //非自动上传，必须手动点击之后才上传
            ,bindAction: '#btn_up'
            ,multiple: true
            ,number: 0
            ,data: {}                   //额外参数
            ,exts:'xlsx|xls'            //允许上传的文件后缀
            ,size: 4096                 //每个文件大小不能超过4MB
            ,before: function(obj){     //上传前的回调
                this.data.dataType = $("select[name='dataType']").find("option:selected").val();
            }
            ,allDone: function(res){
                var max = res.total * 5;
                loading = layer.msg('正在拼命处理中，该操作可能需要' + (res.total * 5) + "秒钟左右", {icon: 16, shade: 0.3, time:0});
                console.log("==== 开始上传 ====");
                //全部上传完成之后，调用接口处理
                $.ajax({
                    type: "POST",
                    url: "/operation/fullzip/upload-excel-all-done",
                    data: {
                        description: $("input[name='description']").val(),
                        dataType:  $("select[name='dataType']").find("option:selected").val()
                    },
                    datatype: "json",
                    success: function(data){
                        if(data.code === 0){
                            $("#url_addr").text(data.data.download_url);
                            layer.msg('导入成功！');
                        }else{
                            layer.msg(data.msg);
                        }
                        layer.close(loading);
                    },
                    error: function(data){
                        $("#url_addr").text(data.msg);
                        layer.close(loading);
                    }
                });
            }
            ,done: function(res, index){
               if(res.code > 0){
                   layer.msg(res.msg);
               }
            }
            ,error: function(res){
                layer.msg(res.msg);
            }

        });
    });
</script>
</body>
</html>