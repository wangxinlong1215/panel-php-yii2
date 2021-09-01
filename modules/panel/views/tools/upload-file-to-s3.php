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
    <ul class="site-dir layui-layer-wrap"><li>本功能是将本地文件上传到s3服务器，然后通过提供的链接地址可以读取服务器上的文件。</li></ul>
    <div class="layui-collapse">
        <div class="layui-colla-item">
            <h2 class="layui-colla-title">上传目录说明</h2>
            <div class="layui-colla-content">
                <ul>
                    <li>1.会覆盖</li>
                    <li>2.会覆盖</li>
                    <li>3.会覆盖</li>
                </ul>
            </div>
        </div>
        <div class="layui-colla-item">
            <h2 class="layui-colla-title">上传文件说明</h2>
            <div class="layui-colla-content">
                <ul>
                    <li>1.会覆盖</li>
                    <li>2.会覆盖</li>
                    <li>3.会覆盖</li>
                </ul>
            </div>
        </div>
    </div>
</blockquote>
<fieldset class="layui-elem-field">
    <legend>选择文件目录或文件上传到S3存储服务器</legend>
    <div class="layui-field-box">
        <form class="layui-form" action="#">
            <div class="layui-form-item">
                <label class="layui-form-label">上传类型：</label>
                <div class="layui-input-block">
                    <input type="radio" lay-filter="upload" name="upload" value="1" title="上传目录" checked>
                    <input type="radio" lay-filter="upload" name="upload" value="2" title="上传文件">
                </div>
            </div>

            <div class="layui-form-item" id="updir_div">
                <label class="layui-form-label">文件目录：</label>
                <div class="layui-input-block">
                    <input type="text" id="localDirPath" required lay-verify="required" placeholder="请输入要上传的目录地址，例如：/Users/awesome/Downloads/iOS" autocomplete="off" class="layui-input">
                </div>
            </div>

            <div class="layui-form-item layui-hide" id="upfile_div">
                <label class="layui-form-label">选择文件：</label>
                <button type="button" class="layui-btn" id="upfile">
                    <i class="layui-icon" style="color: #00FFFF;">&#xe60a;</i>点击选择要上传的文件
                </button>
            </div>

            <div class="layui-form-item" id="s3dir_div">
                <label class="layui-form-label">S3目录：</label>
                <div class="layui-input-block">
                    <input type="text" id="s3DirPath" required lay-verify="required" placeholder="请输入S3存储目录，例如: /assets/project" autocomplete="off" class="layui-input">
                </div>
            </div>

            <div class="layui-form-item" id="updir_btn_div">
                <div class="layui-input-block">
                    <button class="layui-btn" id="btn_updir">开始上传目录</button>
                    <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                </div>
            </div>

            <div class="layui-form-item layui-hide" id="upfile_btn_div">
                <div class="layui-input-block">
                    <button class="layui-btn" id="btn_upfile">开始上传文件</button>
                    <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                </div>
            </div>
        </form>
    </div>
</fieldset>

<script>
    layui.use(['layer', 'form', 'element', 'upload'], function(){
        var layer = layui.layer, form = layui.form, element = layui.element, $= layui.$;
        var upload = layui.upload;
        form.on('radio(upload)', function(data){
            if(data.value == 1){
                $("#updir_div").show();
                $("#upfile_div").hide();
                $("#updir_btn_div").show();
                $("#upfile_btn_div").hide();

                //当上传目录时，要去掉上传文件时的require组件，否则会报错：An invalid form control with name='' is not focusable.
            }else{
                $("#updir_div").hide();
                $("#upfile_div").removeClass('layui-hide');
                $("#upfile_div").show();
                $("#updir_btn_div").hide();
                $("#upfile_btn_div").removeClass('layui-hide');
                $("#upfile_btn_div").show();

            }
        });

        //执行实例
        var uploadInst = upload.render({
            elem: '#upfile' //绑定元素
            // ,url: '/panel/tools/up-file' //上传接口
            // ,url: '/operation/parts-excel/upload-excel' //上传接口
            ,url: '<?=\yii\helpers\Url::to(['/operation/style-book-excel/upload-excel'])?>'
            ,accept: 'file'
            ,auto: false
            ,bindAction: '#btn_upfile'
            ,multiple: false
            ,number: 0
            ,data: {}
            ,exts:'xlsx' | 'xls'
            ,before: function(obj){   //上传前的回调
                //判断s3目录是否为空
                layer.load(2);
            }
            ,done: function(res){
                layer.closeAll('loading');
                //上传完毕回调
                layer.msg('上传成功');
            }
            ,error: function(res){
                layer.closeAll('loading');
                //请求异常回调
                // layer.msg('上传失败');
            }

        });
    });
</script>
</body>
</html>