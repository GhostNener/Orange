<?php if (!defined('THINK_PATH')) exit();?>﻿<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Orange</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="/Orange/Public/css/bootstrap-theme.css"/>
	<link rel="stylesheet" href="/Orange/Public/css/bootstrap.css"/>
	<link rel="stylesheet" href="/Orange/Public/css/huaxi_css.css" />
	<link rel="shortcut icon" href="/Orange/Public/Img/favicon.png" type="image/x-icon"/>
	<script src="/Orange/Public/js/jquery-1.8.0.min.js"></script>
	<script src="/Orange/Public/js/bootstrap.js"></script>
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
						<a href="#">其他</a>
					</li>
				</ul>
			</div>
			<!-- /.navbar-collapse -->
		</div>
		<div class="container">
			<div class="text-center">
				<h1><?php echo ($cmodel['Title']); ?>-关键字管理</h1>
			</div>
			<br>

			<!-- 分类管理-->
			<form class="form-horizontal" role="form" action="<?php echo U('save');?>" method="post">
				<div class="form-group">
					<input type="hidden" class="form-control "  name="modif" value="<?php echo ($modif); ?>" Readonly>
					<label for="Id" class="col-sm-2 control-label">Id</label>
					<div class="col-sm-10">
						<input type="text" class="form-control " id="cid" placeholder=" Id  " name="Id" value="<?php echo ($model["Id"]); ?>" 
					Readonly></div>
				</div>
				<div class="form-group">
					<label for="CategoryId" class="col-sm-2 control-label">CategoryId</label>
					<div class="col-sm-10">
						<input type="text" class="form-control " id="CategoryId" placeholder=" CategoryId  " name="CategoryId" value="<?php echo ($cmodel['Id']); ?>" readonly></div>
				</div>
				<div class="form-group">
					<label for="Keyword" class="col-sm-2 control-label">Keyword</label>
					<div class="col-sm-10">
						<input type="text" class="form-control " id="Keyword" placeholder=" Keyword  "name="Keyword" value="<?php echo ($model["Keyword"]); ?>" ></div>
				</div>
				<div class="form-group">
					<label for="Status" class="col-sm-2 control-label">Status</label>
					<div class="col-sm-10">
						<input type="text" class="form-control " id="Status" placeholder=" Status  "name="Status" value="<?php echo ($model["Status"]); ?>" Readonly></div>
				</div>
				<div class="form-group">
					<label for="Hot" class="col-sm-2 control-label">Hot</label>
					<div class="col-sm-10">
						<input type="num" class="form-control " id="Hot" placeholder=" Hot  "name="Hot" value="<?php echo ($model["Hot"]); ?>" readonly></div>
				</div>
				<div class="form-group">
					<div class="col-sm-offset-2 col-sm-10">
						<button type="submit" class="btn btn-default">保存</button>
						<a class="btn btn-default" href="<?php echo U('index',array('CategoryId'=>$cmodel['Id']));?>">返回</a>
					</div>
				</div>

			</form>
		</div>

	</div>
	<!-- 底栏-->
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