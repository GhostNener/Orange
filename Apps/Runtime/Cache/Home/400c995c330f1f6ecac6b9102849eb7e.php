<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Orange</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="/orange/Public/css/bootstrap-theme.css" />
	<link rel="stylesheet" href="/orange/Public/css/bootstrap.css" />
	<link rel="stylesheet" href="/orange/Public/css/huaxi_css.css" />
	<link rel="shortcut icon" href="/orange/Public/Img/favicon.png"
	type="image/x-icon" />
	<script src="/orange/Public/js/jquery-1.8.0.min.js"></script>
	<script src="/orange/Public/js/bootstrap.js"></script>
</head>
<body>
	<!--顶-->
	<div id="wrap">
		<div class="container">
			<div class="collapse navbar-collapse">
				<ul class="nav navbar-nav">
					<li >
						<a href="<?php echo U('Index/index');?>">Home</a>
					</li>
					<li >
						<a href="<?php echo U('TestDic/index');?>">词典测试</a>
					</li>
					<li>
						<a href="<?php echo U('Goods/index');?>">商品管理</a>
					</li>
					<li>
						<a class="pull-right" href="<?php echo U('Admin/Index/index');?>">后台</a>
					</li>
				</ul>
			</div>
		</div>
		
<div class="container">
	<div class="text-center">
		<h1>
			<a href="<?php echo U('Index/index');?>">Home</a>
		</h1>
	</div>
</div>
	</div>
	<!-- 底栏-->
	<div id="footer" class="text-center">
		<div class="container">
			<span>Power By Juzi</span>
			<a data-toggle="modal"
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
								E-mail:
								<a style="text-decoration: none;"
									href="mailto:493628086@qq.com">493628086@qq.com</a>
							</p>
							<p>
								E-mail:
								<a style="text-decoration: none;"
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