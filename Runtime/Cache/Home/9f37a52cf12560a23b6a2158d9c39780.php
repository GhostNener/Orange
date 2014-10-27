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
					<li><a href="<?php echo U('Home/Index/index');?>">Home</a></li>
					<li><a href="<?php echo U('Home/TestDic/index');?>">词典测试</a></li>
					<li><a href="<?php echo U('Home/Goods/index');?>">商品管理</a></li>
					<li><a class="pull-right" href="<?php echo U('Admin/Index/index');?>">后台</a>
					</li>
					<li><a class="pull-right" href="<?php echo U('Usercenter/User/index');?>">登录</a>
					</li>
				</ul>
			</div>
		</div>
		
<div class="container">
	<!-- 分类管理-->
	<div class="text-center">
		<h1>我的商品列表</h1>
	</div>
	<br>
	<div>
		<a href="<?php echo U('Home/Goods/add');?>" class="btn btn-default">添加</a>
	</div>
	<br>
	<table class="table table-bordered">
		<tr>
			<th class="text-center">Id</th>
			<th class="text-center">Title</th>
			<th class="text-center">Price</th>
			<th class="text-center">CostPrice</th>
			<th class="text-center">Presentation</th>
			<th class="text-center">CategoryId</th>
			<th class="text-center">AddressId</th>
			<th class="text-center">Server</th>
			<th class="text-center">TradeWay</th>
			<th class="text-center">Status</th>
			<th class="text-center">Show</th>
		</tr>
		<?php if(is_array($list)): foreach($list as $key=>$v): ?><tr>
				<td><?php echo ($v["Id"]); ?></td>
				<td><?php echo ($v["Title"]); ?></td>
				<td><?php echo ($v["Price"]); ?></td>
				<td><?php echo ($v["CostPrice"]); ?></td>
				<td><?php echo ($v["Presentation"]); ?></td>
				<td><?php echo ($v["CategoryId"]); ?></td>
				<td><?php echo ($v["AddressId"]); ?></td>
				<td><?php echo ($v["Server"]); ?></td>
				<td><?php echo ($v["TradeWay"]); ?></td>
				<td><?php echo ($v["Status"]); ?></td>
				<td><a href="<?php echo U('Home/Goods/showgoods',array('Id'=>$v['Id']));?>">Show</a></td>
			</tr><?php endforeach; endif; ?>
	</table>
	<?php echo ($page); ?>
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