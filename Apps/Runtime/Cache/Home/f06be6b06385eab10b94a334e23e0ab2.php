<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Orange</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="/Orange/Public/css/bootstrap-theme.css" />
	<link rel="stylesheet" href="/Orange/Public/css/bootstrap.css" />
	<link rel="stylesheet" href="/Orange/Public/css/huaxi_css.css" />
	<link rel="shortcut icon" href="/Orange/Public/Img/favicon.png"
	type="image/x-icon" />
	<script src="/Orange/Public/js/jquery-1.8.0.min.js"></script>
	<script src="/Orange/Public/js/bootstrap.js"></script>
	<script src="/Orange/Public/js/jquery.uploadify.min.js?ver='<?php echo time();?>';?"></script>
	<link rel="stylesheet" href="/Orange/Public/css/uploadify.css">
	<script type="text/javascript">
    $(document).ready(function () {
		function del(delName,delId){			//点击删除链接，ajax
    	var imgId='#'+delName;
		var url=$('#url').attr('appurl')+'/delimg';		//删除图片的路径
         $.post(url,{'Id':delId},function(data){		//ajax后台
            $(imgId).html(data.info);						//输出后台返回信息
            $(imgId).hide(800);							//自动隐藏
        },'json');										//josn格式
	}
		$('#file_upload').uploadify({
			'formData'     : {
				'timestamp' : '<?php echo ($time); ?>',            //时间
				'token'     : '<?php echo (md5($time )); ?>',		//加密字段
				'url'		: $('#url').attr('appurl')+'/upload/',	//url
				'imageUrl'	: $('#url').attr('rooturl')			//root
			},
			'fileTypeDesc' : 'Image Files',					//类型描述
			//'removeCompleted' : false,    //是否自动消失
       		'fileTypeExts' : '*.gif; *.jpg; *.png',		//允许类型
       		'fileSizeLimit' : '3MB',					//允许上传最大值
			'swf'      : $('#url').attr('publicurl')+'/Img/uploadify.swf',	//加载swf
			'uploader' : $('#url').attr('appurl')+'/uploadify',					//上传路径
			'buttonText' :'文件上传',									//按钮的文字
			'onUploadSuccess' : function(file, data, response) {			//成功上传返回
           	var imgId=Math.random()+(new Date()).valueOf();		
           	var tempPath=$('#url').attr('publicurl');						
           	//data  url
           	//插入到image标签内，显示图片的缩略图           
			$('#image').append('<div id="'+imgId+'" class="photo "><a href="'+data+'"  target="_blank"><img src="'+tempPath+data+'"  height=80 width=80 /></a><div class="del"><a href="javascript:void(0)" onclick=del("'+imgId+'","'+data+'");return false;>删除</a></div></div>');
			}
		});
    })
</script>
</head>
<body>
	<!--顶-->
	<div id="wrap">
		<div class="container">
			<div class="collapse navbar-collapse" >
				<ul class="nav navbar-nav">
					<li class="active">
						<a href="<?php echo U('Index/index');?>">Home</a>
					</li>
					<li>
						<a href="<?php echo U('GoodsCategory/index');?>">分类管理</a>
					</li>
					<li>
						<a href="<?php echo U('Home/Index/index');?>">前台</a>
					</li>
				</ul>
			</div>
			<!-- /.navbar-collapse -->
		</div>
		<div class="container">
			<div class="text-center">
				<h1>发布商品</h1>
			</div>
			<br>

			<!-- 分类管理-->
			<form class="form-horizontal" role="form"  method="post">
				<div class="form-group">
					<input type="hidden" class="form-control "  name="UserId" value="13" Readonly>
					<input type="hidden" class="form-control "  name="Status" value="0" Readonly>
					<label for="Title" class="col-sm-2 control-label">Title</label>
					<div class="col-sm-10">
						<input type="text" class="form-control " id="Title" placeholder=" Title  " name="Title" ></div>
				</div>
				<div class="form-group">
					<label for="Price" class="col-sm-2 control-label">Price</label>
					<div class="col-sm-10">
						<input type="text" class="form-control " id="Price" placeholder=" Price  " name="Price" ></div>
				</div>
				<div class="form-group">
					<label for="Presentation" class="col-sm-2 control-label">Presentation</label>
					<div class="col-sm-10">
						<textarea class="form-control " name="Presentation" id="Presentation" cols="30" rows="10"></textarea>
					</div>
				</div>
				<div class="form-group">
					<input  type="hidden"  class="form-control " id="url" publicurl="/Orange/Public" appurl="/Orange/Home/Goods"  rooturl="/Orange" Readonly>
					<label for="file_upload" class="col-sm-2 control-label">file_upload</label>
					<div class="col-sm-10">
						<input id="file_upload" name="file_upload" type="file" multiple="true">
						<div id="image" class="image"></div>
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-offset-2 col-sm-10">
						<button type="submit" class="btn btn-default">保存</button>
						<a class="btn btn-default" href="#">返回</a>
					</div>
				</div>
			</form>
			<div></div>
		</div>

	</div>
	<!---->
	<div id="footer" class="text-center">
		<div class="container" >
			<span>Power By  XXX</span>
			<a data-toggle="modal" data-target="#fingertipModal" >联系</a>
			<div class="modal fade footer_contac" id="fingertipModal" tabindex="-1" role="dialog" aria-labelledby="fingertipModalabel" aria-hidden="true" >
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
							<h4 class="modal-title" id="fingertipModalLabel">指尖科技</h4>
						</div>
						<div class="modal-body">
							<p>
								E-mail:
								<a style="text-decoration: none;" href="mailto:493628086@qq.com">493628086@qq.com</a>
							</p>
							<p>
								E-mail:
								<a style="text-decoration: none;" href="mailto:714571611@qq.com">714571611@qq.com</a>
							</p>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
						</div>
					</div>
					<!-- /.modal-content -->
				</div>
				<!-- /.modal-dialog -->
			</div>
		</div>
	</div>
</body>
</html>