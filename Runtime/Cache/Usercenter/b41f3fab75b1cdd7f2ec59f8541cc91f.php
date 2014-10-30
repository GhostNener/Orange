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
								<a href="<?php echo U('Usercenter/User/index');?>" >登录</a>
							</li>
							<li>
								<a href="<?php echo U('Usercenter/User/regist');?>">注册</a>
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
										<a href="<?php echo U('Usercenter/UserCnter/index');?>">个人中心</a>
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

<style>
.login {
	margin: 200px 100px;
}

.verifycode {
	cursor: pointer;
}
</style>

<script>
	$(function(){
		$('#Nick').focus(function(){
			removemsg('Nick');
		});
		$('#UserName').focus(function(){
			removemsg('UserName');
		});		
		$('#Password').focus(function(){
			removemsg('Password');
		});
		$('#confirmPassword').focus(function(){
			removemsg('confirmPassword');
		});
		$('#registbutton').click(function(){
			var _nick=$.trim($('#Nick').val());
			if(!_nick){
				appendmsg('Nick','昵称不能为空');
				return;

			}		
			var _mail=$.trim($('#UserName').val());
			var _pwd=$.trim($('#Password').val());
			var _confirmpwd=$.trim($('#confirmPassword').val());
			if(!_mail||(!checkmail(_mail)&&!checkmobile(_mail))){
				appendmsg('UserName','用户名不合法');
				return;
			}
			if(!_pwd||!checkpwd(_pwd)){
				appendmsg('Password','密码不合法');
				return;
			}
			if(!_confirmpwd||!checkpwd(_confirmpwd)||_pwd!=_confirmpwd){
				appendmsg('confirmPassword','确认密码不一致');
				return;
			}
			var btn_txt=$('#registbutton').val();
    		$('#registbutton').attr('disabled',"true");
    		$('#registbutton').val('....');
			if(checkmobile(_mail)){
				alert('暂不支持电话哦！');
				$('#registbutton').val(btn_txt);
    			$('#registbutton').removeAttr('disabled'); 
				return false;
			}
			$.post($('#url').attr('regist'),{
				'Password':_pwd,
				'ConfirmPassword':_confirmpwd,
				'Name':_mail,
				'Nick':_nick
			},function(data){

					if(data.status==0){
					alert(data.info);
				}else{
					alert("注册成功！\n 请激活");
					location.href=$('#url').attr('home');
				}
			},'json');
			$('#registbutton').val(btn_txt);
    		$('#registbutton').removeAttr('disabled'); 
		});
		function checkmail(_mail){
			var ismail1=/^([a-z0-9_\.-]+)@([\da-z\.-]+)\.([a-z\.]{2,6})$/;
			var ismail2=/^[a-z\d]+(\.[a-z\d]+)*@([\da-z](-[\da-z])?)+(\.{1,2}[a-z]+)+$/;
			if(!ismail1.test(_mail)&&!ismail2.test(_mail)){
				return false;
			}
			return true;
		}
		function checkpwd(_pwd){
			var ispwd=/^[a-z0-9_A-Z~!@#$%^&*]{6,18}$/;/*/^(?!\D+$)(?!\d+$)[a-zA-Z0-9_]\w{6,16}$/*/;
			if(!ispwd.test(_pwd)){
				return false;
			}
			return true;
		}
		function checkmobile(_uid){
			var isMobile=/^(?:13\d|14\d|15\d|18\d)\d{5}(\d{3}|\*{3})$/; 
			if(!isMobile.test(_uid)){
				return false;
			}
			return true;
		}
		function appendmsg(id,msg){
			removemsg(id);
			var _id='#'+id;
			$(_id).parent().parent().append('<span style="color:red">'+msg+'</span>');
			$(_id).parent().addClass('has-error');
		}
		function removemsg(id){
			var _id='#'+id;
			$(_id).parent().siblings('span').remove();
			$(_id).parent().removeClass('has-error');
		}
	});
</script>

<div id="main" class="container">
<div class="login">
<h1 class="text-center">新用户</h1>
<br>
<form class="form-horizontal" role="form" action="" method="post">
<div class="form-group"><label for="Nick"
	class="col-sm-2 control-label">昵称</label>
<div class="col-sm-8 "><input type="text" class="form-control"
	id="Nick" placeholder="昵称"></div>
</div>
<div class="form-group"><label for="UserName"
	class="col-sm-2 control-label">用户名</label>
<div class="col-sm-8"><input type="email" class="form-control"
	id="UserName" placeholder="邮箱"></div>
</div>
<div class="form-group"><label for="Password"
	class="col-sm-2 control-label">密码</label>
<div class="col-sm-8"><input type="password" class="form-control"
	id="Password" placeholder="密码"></div>
</div>
<div class="form-group"><label for="confirmPassword"
	class="col-sm-2 control-label">确认密码</label>
<div class="col-sm-8"><input type="password" class="form-control"
	id="confirmPassword" placeholder="确认密码"></div>
</div>
<input type="hidden" id="url" value="0"
	regist="<?php echo U('Usercenter/User/signup');?>" home="<?php echo U('Home/Index/index');?>">
<div class="form-group">
<div class="col-sm-offset-2 col-sm-8">
<button type="button" id="registbutton" class="btn btn-default">提交</button>
</div>
</div>
</form>
</div>
</div></div>
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
						<a href="<?php echo U('Admin/Index/index');?>">Copyright &copy; 2014, 指尖科技-橘子团队</a>
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