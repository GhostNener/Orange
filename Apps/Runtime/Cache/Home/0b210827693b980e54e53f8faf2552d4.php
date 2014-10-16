<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Orange</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="/Orange/Public/css/uploadify.css">
	<link rel="stylesheet" href="/Orange/Public/css/bootstrap-theme.css" />
	<link rel="stylesheet" href="/Orange/Public/css/bootstrap.css" />
	<link rel="stylesheet" href="/Orange/Public/css/huaxi_css.css" />
	<link rel="shortcut icon" href="/Orange/Public/Img/favicon.png"
	type="image/x-icon" />
	<script src="/Orange/Public/js/jquery-1.8.0.min.js"></script>
		<script src="/Orange/Public/js/jquery.uploadify.min.js?ver='<?php echo time();?>';?"></script>
	<script src="/Orange/Public/js/bootstrap.js"></script>
	<script type="text/javascript">
	function del(div_id,imgurl){			//点击删除链接，ajax
    	var imgId='#'+div_id;
		var url=$('#url').attr('appurl')+'/delimg';		//删除图片的路径
         $.post(url,{'URL':imgurl},function(data){		//ajax后台
            $(imgId).html(data.info);						//输出后台返回信息
            $(imgId).hide(800);							//自动隐藏
        },'json');										//josn格式
	}
    $(document).ready(function () {
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
			'onUploadSuccess' : function(file, data, response) {
				if(data){
					 data=$.parseJSON(data);
					if(!($.isNumeric(data[0]))){
						alert('上传失败！');
						return;
					}
					var gid=$('#url').attr('gid');
					if(1){
						var postimgurl=$('#url').attr('appurl')+'/saveimg';
						$.post(postimgurl,{
							'_imgid':data[0],
								'_gid':gid

						},function(datainfo){
						if(datainfo.info){
							$('#url').attr('gid',datainfo.info);
	//成功上传返回
           	var imgId='_'+(parseInt(Math.random() *100)+(new Date()).valueOf());		
           	var tempPath=$('#url').attr('publicurl');						
           	//data  url
           	//插入到image标签内，显示图片的缩略图           
			$('#image').append('<div id="'+imgId+'" class="photo" style="margin-right: 3px;margin-left: 3px" ><img src="'+tempPath+data[1]+'"  height=80 width=80 /><div class="del"><a class="a_imgdel" href="javascript:void(0)"onclick=del("'+imgId+'","'+data[1]+'");return false; imgurl="'+data[1]+'">删除</a></div></div>');return;
		}else{
			alert('上传失败！');
			return;
		}
						});						
					}
				}else{
			alert('上传失败！');
			return;
		}

			}
		});
    })
</script>
</head>
<body>
<a class="a_imgdel" href="javascript:void(0)"  imgurl="'+data[1]+'">删除</a>
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
			<form class="form-horizontal" action="<?php echo U('save');?>" role="form"  method="post" multiple="true" >
				<div class="form-group">
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
					<label for="Category" class="col-sm-2 control-label">Category</label>
					<div class="col-sm-10">
						<select class="form-control " name="Category" id="Category">
							<?php if(is_array($clist)): foreach($clist as $key=>$v): ?><option value="<?php echo ($v['Id']); ?>"><?php echo ($v['Title']); ?></option><?php endforeach; endif; ?>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label for="Address" class="col-sm-2 control-label">Address</label>
					<div class="col-sm-8">
						<select class="form-control " name="Address" id="Address">
							<?php if(is_array($clist)): foreach($clist as $key=>$v): ?><option value="<?php echo ($v['Id']); ?>"><?php echo ($v['Title']); ?></option><?php endforeach; endif; ?>

						</select>
					</div>
					<div class="col-sm-2">
					 <div class="pull-left">
							<a href="" class="btn btn-default">添加地址</a>
						</div>
						</div>
				</div>
				<div class="form-group">
					<input  type="hidden"  class="form-control " id="url" publicurl="/Orange/Public" appurl="/Orange/Home/Goods"  rooturl="/Orange" gid="0" Readonly>
					<label for="file_upload" class="col-sm-2 control-label">file_upload</label>
					<div class="col-sm-10">
						<input id="file_upload" name="file_upload" type="file" multiple="true">
						<div id="image" class="image col-sm-12"></div>
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