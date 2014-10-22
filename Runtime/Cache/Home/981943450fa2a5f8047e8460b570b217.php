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
</head>
<body>
	<!--顶-->
	<div id="wrap">
		<div class="container">
			<div class="collapse navbar-collapse">
				<ul class="nav navbar-nav">
					<li><a href="<?php echo U('Index/index');?>">Home</a></li>
					<li><a href="<?php echo U('TestDic/index');?>">词典测试</a></li>
					<li><a href="<?php echo U('Goods/index');?>">商品管理</a></li>
					<li><a class="pull-right" href="<?php echo U('Admin/Index/index');?>">后台</a>
					</li>
				</ul>
			</div>
		</div>
		﻿
<script>
	$(document).ready(function(){
		$('#addressSubmit').click(function(){
			/*验证手机*/
			var isMobile=/^(?:13\d|14\d|15\d|18\d)\d{5}(\d{3}|\*{3})$/; 
			/*var isPhone=/^((0\d{2,3})-)?(\d{7,8})(-(\d{3,}))?$/;*/
			var _suburl=$(this).attr('acurl');
			var _modif=$('#modif').val();
			var _Id=$('#Id').val();
			var _Tel=$('#Tel').val();
			if(!isMobile.test(_Tel)){
				$('#Tel').focus();
				return;
			}
			var _QQ=$('#QQ').val();
			var _Address=$('#Address').val();
			var _IsDefault=$('#IsDefault').attr('checked');
			if(!_IsDefault){_IsDefault=0}else{_IsDefault=1;}
			$.post(_suburl,{
				'modif':_modif,
				'Id':_Id,
				'Tel':_Tel,
				'QQ':_QQ,
				'Address':_Address,
				'IsDefault':_IsDefault
			},function(data){
				if(data.status==0){
					alert(data.info);
					return;
				}
				location.href=$('#addressSubmit').attr('urlindex');
			});
		});
	});
</script>
<div class="container">
	<div class="text-center">
		<h1>地址管理</h1>
	</div>
	<br>
	<!-- 分类管理-->
	<form class="form-horizontal" role="form" action="" method="post">
		<div class="form-group">
			<input type="hidden" id="modif" class="form-control " name="modif"
				value="<?php echo ($modif); ?>" Readonly> <label for="Id"
				class="col-sm-2 control-label">Id</label>
			<div class="col-sm-10">
				<input type="text" class="form-control " id="Id" placeholder=" Id  "
					name="Id" value="<?php echo ($model["Id"]); ?>" Readonly>
			</div>
		</div>
		<div class="form-group">
			<label for="Tel" class="col-sm-2 control-label">Tel</label>
			<div class="col-sm-10">
				<input type="text" class="form-control " id="Tel"
					placeholder=" Tel  " name="Tel" value="<?php echo ($model["Tel"]); ?>">
			</div>
		</div>
		<div class="form-group">
			<label for="QQ" class="col-sm-2 control-label">QQ</label>
			<div class="col-sm-10">
				<input type="text" class="form-control " id="QQ" placeholder=" QQ  "
					name="QQ" value="<?php echo ($model["QQ"]); ?>">
			</div>
		</div>
		<div class="form-group">
			<label for="Address" class="col-sm-2 control-label">Address</label>
			<div class="col-sm-10">
				<input type="text" class="form-control " id="Address"
					placeholder=" Address  " name="Address" value="<?php echo ($model["Address"]); ?>">
			</div>
		</div>
		<div class="form-group">
			<label for="IsDefault" class="col-sm-2 control-label">IsDefault</label>
			<div class="col-sm-10">
				<label class="checkbox-inline"> <input type="checkbox"
					id="IsDefault" value="1">作为默认地址
				</label>
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
				<button id='addressSubmit' type="button" urlindex="<?php echo U('index');?>"
					acurl="<?php echo U('saveaddress');?>" class="btn btn-default">保存</button>
				<a class="btn btn-default" href="<?php echo U('index');?>">返回</a>
			</div>
		</div>
	</form>
</div>
	</div>
	<!-- 底栏-->
	<div id="footer" class="text-center">
		<div class="container">
			<span>Power By Juzi</span> <a data-toggle="modal"
				data-target="#fingertipModal">联系</a>
			<div class="modal fade footer_contac" id="fingertipModal"
				tabindex="-1" role="dialog" aria-labelledby="fingertipModalabel"
				aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal"
								aria-hidden="true">×</button>
							<h4 class="modal-title" id="fingertipModalLabel">指尖科技</h4>
						</div>
						<div class="modal-body">
							<p>
								E-mail: <a style="text-decoration: none;"
									href="mailto:493628086@qq.com">493628086@qq.com</a>
							</p>
							<p>
								E-mail: <a style="text-decoration: none;"
									href="mailto:714571611@qq.com">714571611@qq.com</a>
							</p>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default"
								data-dismiss="modal">关闭</button>
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