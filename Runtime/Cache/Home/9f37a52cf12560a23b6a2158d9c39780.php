<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh">
<head>

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
	<meta name="description" content="贵州财经大学-二手交易-以物换物-服务平台-c2c">
	<meta name="author" content="橘子团队">
	<title>大橘子-贵财二手交易平台</title>
	<!-- Bootstrap Core CSS -->
	<link href="/Orange/Public/css/bootstrap.min.css" rel="stylesheet">
	<link href="/Orange/Public/css/normalize.css" rel="stylesheet">
	<!-- Custom CSS -->
	<link href="/Orange/Public/css/juzi.css" rel="stylesheet">
	<link rel="shortcut icon" href="/Orange/Public/Img/favicon.png"
	type="image/x-icon" />
	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
	<script src="http://cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
	<script src="http://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
	<![endif]-->
	<script src="/Orange/Public/js/jquery-1.11.0.js"></script>
	<script src="/Orange/Public/js/bootstrap.min.js"></script>
	<script src="/Orange/Public/js/juzi.js"></script>
</head>

<body>
	<!-- 页身 -->
	<div id="wrap">
		<!-- 导航 -->
		<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
			<div class="container">
				<!-- Brand and toggle get grouped for better mobile display -->
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="<?php echo U('Home/Index/index');?>">logo 大橘子</a>
				</div>

				<!-- Collect the nav links, forms, and other content for toggling -->
				<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
					<ul class="nav navbar-nav">
						<li class="active">
							<a href="<?php echo U('Home/Index/index');?>">首页</a>
						</li>
											<li>
						<a href="<?php echo U('Home/Goods/index');?>">商品管理</a>
					</li>
						<li>
							<a href="#">发现</a>
						</li>
						<li>
							<a href="#">
								礼品
								<span class="navbar-new"></span>
							</a>
						</li>
					</ul>
					<form class="navbar-form navbar-left" role="search" action="<?php echo U('Home/Index/searchgoods');?>" method="post">
						<div class="input-group">
							<input type="text" class="form-control " id="text" placeholder="搜索商品" name="text" value="<?php echo ($test); ?>">
							<div class="input-group-btn">
								<button type="submit" class="btn btn-default btn-expend">
									<span class="glyphicon glyphicon-search"></span>
								</button>
							</div>
							<!-- /btn-group -->
						</div>
						<!-- /input-group -->
					</form>

					<!-- 未登录状态 -->
					<?php if($usermodel == null): ?><ul class="nav navbar-nav navbar-right">
							<li>
								<a href="#" data-toggle="modal" data-target=".bs-example-modal-sm">登录</a>
							</li>
							<li>
								<a href="#">注册</a>
							</li>
						</ul>
						<?php else: ?>
						<!-- 登录状态 -->
						<ul class="nav navbar-nav navbar-right">
							<li class="dropdown">
								<a class="dropdown-toggle" data-toggle="dropdown" href="#">
									<img src="http://hhhhold.com/18x18">
									&nbsp<?php echo ($usermodel['Nick']); ?>&nbsp
									<span class="caret"></span>
									&nbsp
									<span class="badge">3</span>
								</a>
								<ul class="dropdown-menu">
									<li>
										<a href="#">
											<span class="badge pull-right">3</span>
											未读消息
										</a>
									</li>
									<li>
										<a href="#">个人中心</a>
									</li>
									<li>
										<a href="#">心愿单</a>
									</li>
									<li>
										<a href="#">充值</a>
									</li>
									<li class="divider"></li>
									<li>
										<a href="#">退出用户</a>
									</li>
								</ul>
							</li>
						</ul><?php endif; ?>
				</div>
				<!-- /.navbar-collapse -->
			</div>
			<!-- /.container-fluid -->
		</nav>

		<!-- 内容 -->

<div id="main" class="container">
	<div class="text-center">
		<h1>我的商品列表</h1>
	</div>
	<br>
	<div class="text-center">
		<a href="<?php echo U('Home/Goods/add');?>" class="btn btn-default">添加</a>
	</div>
	<br>

	<div class="row">
		<!-- 商品列表 -->
		<?php if(is_array($list)): foreach($list as $key=>$v): ?><div class="col-sm-4 col-lg-4 col-md-4">
				<div class="thumbnail">
					<img src="/Orange<?php echo ($v['ThumbURL']); ?>" alt="">
					<div class="caption">
						<h4 class="pull-right message"><?php echo ($v['TradeWayTxt']); ?></h4>
						<p>
							<img src="http://hhhhold.com/40x40" alt="头像" class="img-circle">
							<span><?php echo ($v['Nick']); ?></span>
						</p>
						<p><?php echo ($v['Title']); ?></p>
					</div>
					<div class="ratings">
						<h4 class="pull-right message">浏览 <?php echo ($v['Views']); ?></h4>
						<h4 class="pull-right message">评论 <?php echo ($v['CommentCount']); ?>&nbsp</h4>
						<h3>
							<?php echo ($v['Price']); ?>
							<span><?php echo ($v['CostPrice']); ?></span>
						</h3>
					</div>
				</div>
			</div><?php endforeach; endif; ?>
	</div>
	<div class="row">
		<br>
		<?php echo ($page); ?>
		<br></div>
</div></div>
	<!-- 登录 -->
	<?php if($usermodel == null): ?><div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-sm">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">
							<span aria-hidden="true">×</span>
							<span class="sr-only">Close</span>
						</button>
						<h4 class="modal-title" id="mySmallModalLabel">欢迎回来</h4>
					</div>
					<form class="form-horizontal" role="form">
						<div class="modal-body">
							<div class="form-group">
								<label for="username" class="col-sm-3 control-label">用户名</label>
								<div class="col-sm-9">
									<input type="text" name="username" id="username" class="form-control" placeholder="邮箱/手机号" id="username"></div>
							</div>
							<div class="form-group">
								<label for="password" class="col-sm-3 control-label">密码</label>
								<div class="col-sm-9">
									<input type="password" name="password" id="password" class="form-control" id="password"></div>
							</div>
							<div class="form-group">
								<label for="verify" class="col-sm-3 control-label">验证码</label>
								<div class="col-sm-9">
									<input type="text" class="form-control" id="verify"></div>
							</div>
							<div class="form-group">
								<div class="col-sm-offset-3 col-sm-9">
									<img src="http://10.200.10.90:88/Orange/Usercenter/Public/verifycode.html" width="150" height="60"></div>
							</div>
							<div class="form-group">
								<div class="col-sm-offset-3 col-sm-9">
									<label class="rememberme">
										<input type="checkbox">&nbsp请记住我（30天）</label>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<a href="#">忘记密码？</a>
							<button type="submit" class="btn btn-success">登录</button>
						</div>
					</form>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div><?php endif; ?>
	<!-- 页脚 -->
	<footer>
		<div class="container">
			<div class="row hidden-xs">
				<div class="col-md-5">
					<p> <b>介绍</b>
					</p>
					<p>Logo design by 某某某</p>
					<p>Powerd by ThinkPHP</p>
					<p>Copyright &copy; 2014, 指尖科技-橘子团队</p>
					<p>
						<a href="http://www.miibeian.gov.cn/">黔ICP备14004869号-1</a>
					</p>
				</div>
				<div class="col-md-5">
					<p> <b>关于</b>
					</p>
					<p>
						<a href="#">橘子团队</a>
					</p>
					<p>
						<a href="#">问题反馈</a>
					</p>
					<p>
						<a href="#">联系我们</a>
					</p>
					<p>
						<a href="#">免责声明</a>
					</p>
				</div>
				<div class="col-md-2">
					<p>
						<b>客户端下载(android)</b>
					</p>
					<img src="http://hhhhold.com/110x110" alt=""></div>
			</div>
			<div class="row visible-xs-inline">
				<div class="col-md-12 text-center">
					<p>
						<a href="#">Copyright &copy; 2014, 指尖科技-橘子团队</a>
					</p>
					<p>
						<a href="http://www.miibeian.gov.cn/">黔ICP备14004869号-1</a>
					</p>
				</div>

			</div>
		</div>	
	</footer>
</body>
</html>