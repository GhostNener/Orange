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
	<style>
	.login{

		margin: 200px  100px;
	}
</style>
</head>
<body>
	<!--顶-->
	<div id="wrap">
		<div class="container">

			<div class="login">
				<form class="form-horizontal" role="form" action="<?php echo U('login');?>" method="post">
					<div class="form-group">
						<label for="inputEmail3" class="col-sm-2 control-label">UserName</label>
						<div class="col-sm-10">
							<input type="UserName" class="form-control" id="inputEmail3" placeholder="Email"></div>
					</div>
					<div class="form-group">
						<label for="inputPassword3" class="col-sm-2 control-label">Password</label>
						<div class="col-sm-10">
							<input type="password" class="form-control" id="inputPassword3" placeholder="Password"></div>
					</div>
					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-10">
							<div class="checkbox">
								<label>
									<input  type="checkbox">Remember me</label>
							</div>
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-10">
							<button type="submit" class="btn btn-default">Sign in</button>
							<a  href="<?php echo U('regist');?>" class="btn btn-default">Sign up</a>
						</div>
					</div>
				</form>
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