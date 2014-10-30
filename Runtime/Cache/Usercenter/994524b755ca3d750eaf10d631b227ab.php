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
					<!-- 登录标识 -->

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
		<!-- 内容{__CONTENT__} -->
		

<style>
	.login{

		margin: 200px  100px;
	}
	.verifycode{
		cursor: pointer;
	}

</style>

<div id="main" class="container">
	<div class="login">
		<form class="form-horizontal" role="form" action="" method="post">
			<div class="form-group">
				<input type="hidden" id="isadmin" value="<?php echo ($admin); ?>">
				<label for="UserName" class="col-sm-2 control-label">用户名</label>
				<div class="col-sm-10">
					<input type="txt" class="form-control" id="UserName" placeholder="用户名"></div>
			</div>
			<div class="form-group">
				<label for="Password" class="col-sm-2 control-label">密码</label>
				<div class="col-sm-10">
					<input type="password" class="form-control" id="Password" placeholder="密码"></div>
			</div>
			<div class="form-group has-feedback">
				<label for="verifycode" class="col-sm-2 control-label">验证码</label>
				<div class="col-sm-10 ">
					<input type="txt" class="form-control " status="0" id="verifycode" placeholder="验证码"></div>
			</div>
			<div class="form-group">
				<label for="verifycode" class="col-sm-2 control-label"></label>
				<div class="col-sm-10">
					<img class="verifycode" src="<?php echo U('Usercenter/Public/verifycode');?>" alt="点击刷新" title="点击刷新"></div>
			</div>
			<input type="hidden" id="url" value="0" getcode="<?php echo U('Public/verifycode');?>" checkcode="<?php echo U('Usercenter/Public/check_verify');?>" login="<?php echo U('Usercenter/User/login');?>" home="<?php echo U('Home/Index/index');?>" >
			<input type="hidden" value="0" name="rememberme"  id="isremeber">
			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
					<div class="checkbox">
						
							<?php if( ($admin != 1) AND ($admin != true)): ?><label><input id="rememberme" type="checkbox">记住我</label><?php endif; ?>
					</div>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
					<button type="button" id="loginbutton" class="btn btn-default">登录</button>
					<a  href="<?php echo U('Usercenter/User/regist');?>" class="btn btn-default">注册</a>
				</div>
			</div>
		</form>
	</div>
</div>

		<!-- 内容{__CONTENT__} -->

	</div>
	页脚
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
						<a target="_blank" href="<?php echo U('Admin/Index/index');?>">橘子团队</a>
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
	<script src="/Orange/Public/js/jquery-1.11.0.js"></script>
	<script src="/Orange/Public/js/bootstrap.min.js"></script>
	<script src="/Orange/Public/js/juzi.js"></script>
	<!-- 特殊js -->
	
	
<script>
	$(function(){
		/*验证码自动验证*/
/*		$('#verifycode').blur(function(){
			var _code=$.trim($(this).val());
			if(!_code){return;}
			$.post($('#url').attr('checkcode'),{'code':_code},function(data){
				if(!data){
					$('#verifycode').parent().removeClass('has-error');
					$('#verifycode').parent().addClass('has-error');
					$('#verifycode').attr('status',0);

				}else{
					$('#verifycode').parent().removeClass('has-error');
					$('#verifycode').parent().addClass('has-success');
					$('#verifycode').attr('status',1);
				}
			});
		});*/
/*		$('#verifycode').focus(function(){
			$('#verifycode').parent().removeClass('has-error');
		});*/
		/*登录按钮*/
		$('#loginbutton').click(function(e){
			$('#loginbutton').attr('disabled',"true");
    		$('#loginbutton').val('....');
			var _uid=$.trim($('#UserName').val());
			if(!_uid){
				$('#UserName').val('');
				$('#UserName').focus();
			$('#loginbutton').val(btn_txt);
    		$('#loginbutton').removeAttr('disabled'); 
				return;
			}
			var _pwd=$('#Password').val();
			if(!$.trim(_pwd)){
				$('#Password').val('');
				$('#Password').focus();
			$('#loginbutton').val(btn_txt);
    		$('#loginbutton').removeAttr('disabled'); 
				return;
			}
			var _isadmin=$('#isadmin').val();			
			/*记住我*/
			var _remember=parseInt($('#isremeber').val());
			var _code=$.trim($('#verifycode').val());
			/*var _status=parseInt($('#verifycode').attr('status'));*/
/*			if(!_code||!_status){
				$('#verifycode').attr('status',0);
				$('#verifycode').focus();
				return;
			}*/
			/**/
		if(!_isadmin){
			_remember=_remember;
		}else{
			_remember=0;
		}
		    var btn_txt=$('#loginbutton').val();
			/*提交表单*/
			$.post($('#url').attr('login'),{
				'Name':_uid,
				'Password':_pwd,
				'verifycode':_code,
				'isremeber':_remember,
				'isadmin':_isadmin
			},function(data){
				if(data.status==1){
					if(_isadmin){
						location.href=$('#adminrul').attr('href');
					}else{
					location.href=$('#url').attr('home');}


				}else{
					reloadcode();
					alert(data.info);
					 reloadcode();
				}
			},'json');
    		$('#loginbutton').val(btn_txt);
    		$('#loginbutton').removeAttr('disabled'); 
		});
		/*记住我按钮*/
		$('#rememberme').click(function(e){
			if(!$(this).attr('checked')){
				$('#isremeber').val(0);
			}else{
				$('#isremeber').val(1);
			}
		});
		$('.verifycode').click(function(e){
			reloadcode();
		});
		/*刷新验证码*/
		function reloadcode(){
			var _src=$('#url').attr('getcode')+'?id='+ new Date().valueOf() ;
			$('.verifycode').attr('src',_src);
			$('#verifycode').parent().removeClass('has-error');
			$('#verifycode').parent().removeClass('has-success');
			//$('#verifycode').parent().addClass('has-error');
			$('#verifycode').attr('status',0);

		}
	});
</script>

	<!-- 特殊css -->
	
</body>
</html>