<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="textml; charset=UTF-8">
<title>添加商品</title>
<script src="/Orange/Public/uploadify/jquery.min.js" type="text/javascript"></script>
<script src="/Orange/Public/uploadify/jquery.uploadify.min.js"
	type="text/javascript"></script>
<link rel="stylesheet" type="text/css"
	href="/Orange/Public/uploadify/uploadify.css">
</head>
<body>
<div><span> <span style="float: left">当前位置是：发布商品</span> <span
	style="float: right; margin-right: 8px; font-weight: bold"> <a
	href="">【返回】</a> </span> </span></div>
<div style="font-size: 13px; margin: 10px 5px">
<form action="/Orange/Home/Goods/add" method="post"
	enctype="multipart/form-data"><input type="hidden" name="UserId"
	value="13" /> <input type="hidden" name="Status" value="0" />
<table border="1" width="100%" class="table_a">
	<tr>
		<td>商品名称</td>
		<td><input type="text" name="Title" /></td>
	</tr>
	<tr>
		<td>商品价格</td>
		<td><input type="text" name="Price" /></td>
	</tr>
	<tr>
		<td>商品详细描述</td>
		<td><textarea name="Presentation"></textarea></td>
	</tr>
	<tr>
		<td colspan="2" align="center"><input type="submit" value="添加">
		</td>
	</tr>
</table>
</form>
</div>
<div>
<form action=""><input id="file_upload" name="file_upload" type="file"
	multiple="true">
<div id="image" class="image"></div>
<input type="hidden" id="url" value="/Orange/Home/Goods"> <input
	type="hidden" id="root" value="/Orange"> <input type="hidden"
	id="public" value="/Orange/Public"></form>
<script type="text/javascript">
	function del(delName,delId){			//点击删除链接，ajax
    	var info=$('#url').val();  //获取url
    	var d='#'+delName;
		var url=info+"/del";		//删除图片的路径
         $.post(url,{'name':delId},function(data){		//ajax后台
            $(d).html(data.info);						//输出后台返回信息
            $(d).hide(3000);							//自动隐藏
        },'json');										//josn格式
	}

	$(function() {
		$('#file_upload').uploadify({
			'formData'     : {
				'timestamp' : '<?php echo ($time); ?>',            //时间
				'token'     : '<?php echo (md5($time )); ?>',		//加密字段
				'url'		: $('#url').val()+'/upload/',	//url
				'imageUrl'	: $('#root').val()				//root
			},

			'fileTypeDesc' : 'Image Files',					//类型描述
			//'removeCompleted' : false,    //是否自动消失
       		'fileTypeExts' : '*.gif; *.jpg; *.png',		//允许类型
       		'fileSizeLimit' : '3MB',					//允许上传最大值
			'swf'      : $('#public').val()+'/uploadify/uploadify.swf',	//加载swf
			'uploader' : $('#url').val()+'/uploadify',					//上传路径
			'buttonText' :'文件上传',									//按钮的文字

			'onUploadSuccess' : function(file, data, response) {			//成功上传返回
           	var n=parseInt(Math.random()*100);								//100以内的随机数
           	//alert(n+data);
           	alert('文件 ' + file.name + ' 上传成功.详细信息： ' + response + ':' + data);
           	
           	//插入到image标签内，显示图片的缩略图
           	var tempPath=$('#public').val();
			$('#image').append('<div id="'+n+'" class="photo"><a href="'+data+'"  target="_blank"><img src="'+tempPath+data+'"  height=80 width=80 /></a><div class="del"><a href="javascript:vo(0)" onclick=del("'+n+'","'+data+'");return false;>删除</a></div></div>');

			}
		});
	});
</script>
</div>
<h1><?php echo ($cookie); ?></h1>
</body>
</html>