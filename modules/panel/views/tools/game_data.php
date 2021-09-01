<?php

use yii\helpers\Url;

$SERVER = \yii\helpers\ArrayHelper::getValue($SERVER, 'list', []);
$CLIENT = \yii\helpers\ArrayHelper::getValue($CLIENT, 'list', []);
?>
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
    上传Excel文件，提示变更内容， 写入ADA_DATA并生成full.zip，上传S3服务
</blockquote>


<div class="layui-row">
    <div class="layui-col-md12">
        <div class="layui-row grid-demo">
            <div class="layui-col-md12">
                <div class="grid-demo grid-demo-bg1">
                    <ul class="site-dir layui-layer-wrap">
                        <li>
                            <div class="layui-tab layui-tab-card">
                                <ul class="layui-tab-title">
                                    <li class="layui-this">SERVER</li>
                                    <li>CLIENT</li>
                                </ul>
                                <div class="layui-tab-content">
                                    <!-- SERVER [S] -->
                                    <div class="layui-tab-item layui-show">
                                        <button type="button" class="createFullZip layui-btn " raw-type="SERVER" >生成 SERVER full.zip</button>
                                        <table class="layui-table" lay-skin="table" lay-size="sm">
                                            <colgroup>
                                                <col width="150">
                                                <col width="200">
                                                <col >
                                            </colgroup>
                                            <thead>
                                            <tr>
                                                <th>文件名</th>
                                                <th>最后更新时间</th>
                                                <th>操作</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php if(!empty($SERVER)): ?>
                                                <?php foreach((array)$SERVER as $row):?>
                                                    <tr>
                                                        <td><?=$row['file_name']?></td>
                                                        <td><?=$row['file_mtime']?></td>
                                                        <td>
                                                            <button type="button" class="uploadExcel layui-btn" raw-type="SERVER" raw-data="<?=$row['file_name']?>">上传</button>
                                                            <a class="layui-btn" href="<?=Url::to(['/operation/fullzip/download-excel', 'dataType' => 'SERVER','repoKeyName'=>$row['file_name']],true)?>">下载Excel</a>
                                                            <a class="layui-btn"  href="<?=Url::to(['/operation/fullzip/download-json', 'dataType' => 'SERVER','repoKeyName'=>$row['file_name']],true)?>">下载Json</a>
                                                        </td>
                                                    </tr>
                                                <?php endforeach;?>
                                            <?php endif;?>
                                            </tbody>
                                        </table>
                                        <div class="clearfix"></div>
                                    </div>
                                    <!-- SERVER [E] -->

                                    <!-- CLIENT [S] -->
                                    <div class="layui-tab-item">
                                        <button type="button" class="createFullZip layui-btn" raw-type="CLIENT" >生成 CLIENT full.zip</button>

                                        <table class="layui-table" lay-skin="table" lay-size="sm">
                                            <colgroup>
                                                <col width="150">
                                                <col width="200">
                                                <col >
                                            </colgroup>
                                            <thead>
                                            <tr>
                                                <th>文件名</th>
                                                <th>最后更新时间</th>
                                                <th>操作</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php if(!empty($CLIENT)): ?>
                                                <?php foreach((array)$CLIENT as $row):?>
                                                    <tr>
                                                        <td><?=$row['file_name']?></td>
                                                        <td><?=$row['file_mtime']?></td>
                                                        <td>
                                                            <button type="button" class="uploadExcel layui-btn" raw-type="CLIENT" raw-data="<?=$row['file_name']?>">上传</button>
                                                            <a class="layui-btn" href="<?=Url::to(['/operation/fullzip/download-excel', 'dataType' => 'CLIENT','repoKeyName'=>$row['file_name']],true)?>">下载Excel</a>
                                                            <a class="layui-btn" href="<?=Url::to(['/operation/fullzip/download-json', 'dataType' => 'CLIENT','repoKeyName'=>$row['file_name']],true)?>">下载Json</a>
                                                        </td>
                                                    </tr>
                                                <?php endforeach;?>
                                            <?php endif;?>
                                            </tbody>
                                        </table>
                                        <div class="clearfix"></div>
                                    </div>
                                    <!-- CLIENT [E] -->
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

    $(function () {
        $(".createFullZip").on('click', function () {
            var loding;
            var _dataType = $(this).attr('raw-type');
            console.log($(this).attr('raw-type'));
            //例子2
            layer.prompt({
                formType: 0,
                value: '',
                title: '请输入 '+_dataType+' full.zip 版本相关描述',
                maxlength: 255,
                area: ['800px', '350px'], //自定义文本域宽高
                maxmin: false,
                //text: ['480px', '100'] //自定义文本域宽高
            }, function(value, index, elem){
                if(value){
                    loading = layer.load(2,{ //icon支持传入0-2
                        shade: [0.5, 'gray'], //0.5透明度的灰色背景
                        content: '正在处理中...',
                        success: function (layero) {
                            layero.find('.layui-layer-content').css({
                                'padding-top': '39px',
                                'width': '100px'
                            });
                        }
                    });
                    $.ajax({
                        type: "POST",
                        url: "/operation/fullzip/generate",
                        data: {
                            description: value,
                            dataType: _dataType
                        },
                        datatype: "json",
                        success: function(res){
                            console.log("--- /operation/fullzip/generate response ---");
                            if(res.code === 0){
                                layer.alert("Success! <br />" +
                                    "version: " +res.data.version+ "<br /> " +
                                    "url: " + res.data.download_url + "<br />",{area:"500px",icon:1,closeBtn:0}, function (t) {
                                    layer.close(loading);
                                    layer.close(t);
                                    layer.close(index);
                                });
                            }else{
                                layer.alert(res.msg);
                            }

                        },
                        error: function(res){
                            layer.alert(res.msg);
                        }
                    });
                }
            });
        })
    })

    layui.use(['upload', 'element','table'], function(){

        var layer = layui.layer,
            $ = layui.jquery,
            upload = layui.upload,
            element = layui.element,
            table = layui.table;
        var loading;

        //执行实例
        var uploadInst = upload.render({
            elem: '.uploadExcel' //绑定元素
            ,url: '/operation/fullzip/excel-diff'
            ,accept: 'file' //普通文件
            ,auto: true   //非自动上传，必须手动点击之后才上传
            ,bindAction: '#btn_up'
            ,multiple: true
            ,drag: false
            ,number: 0
            ,data: {}                   //额外参数
            ,exts:'xlsx|xls'            //允许上传的文件后缀
            ,size: 4096                 //每个文件大小不能超过4MB
            ,before: function(obj){
                var item = this.item
                this.data.dataType = item.attr('raw-type');
                this.data.repoKeyName = item.attr('raw-data');
                loading = layer.load(2,{ //icon支持传入0-2
                    shade: [0.5, 'gray'], //0.5透明度的灰色背景
                    content: '正在处理中...',
                    success: function (layero) {
                        layero.find('.layui-layer-content').css({
                            'padding-top': '39px',
                            'width': '100px'
                        });
                    }
                });
            }
            ,done: function(res,index,upload){
                console.log("--- 上传完成 diff response ---");

                if(res.code != 0){
                    layer.msg(res.msg);
                    return false;
                }
                layer.close(loading);

                // 确认上传
                layer.confirm("是否确认上传？",{
                        title:'确认上传',
                        content:res.data.diffResultTable,
                        btn: ["确定","取消"], //按钮
                        area:'auto',
                        maxWidth: '100%'
                    },
                    // 确认按钮 回调
                    function(index){
                        if(res.data.hasDiff == 0){
                            layer.close(index);
                            return false;
                        }
                        loading = layer.load(2,{ //icon支持传入0-2
                            shade: [0.5, 'gray'], //0.5透明度的灰色背景
                            content: '正在处理中...',
                            success: function (layero) {
                                layero.find('.layui-layer-content').css({
                                    'padding-top': '39px',
                                    'width': '100px'
                                });
                            }
                        });
                        $.ajax({
                            type: "POST",
                            url: "/operation/fullzip/excel-confirm-upload",
                            data: {
                                repoKeyName: res.data.repoKeyName,
                                dataType:  res.data.dataType
                            },
                            datatype: "json",
                            success: function(data){
                                console.log("--- 确认上传 response ---");
                                if(data.code === 0){
                                    layer.msg(data.msg);
                                }else{
                                    layer.msg(data.msg);
                                }
                                layer.close(loading);
                            },
                            error: function(data){
                                layer.msg(data.msg);
                                layer.close(loading);
                            }
                        });

                        layer.close(index);
                    },
                    // 取消按钮 回调
                    function(){
                        console.log("取消上传");
                    }
                );// )))layer.confirm

            }
            ,error: function(res){
                console.log(res)
                layer.msg(res.msg);
            }

        });
    });
</script>
</body>
</html>