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
					<li><a class="pull-right" id='adminrul' href="<?php echo U('Admin/Index/index');?>">后台</a>
					</li>
					<li><a class="pull-right" href="<?php echo U('Usercenter/User/index');?>">登录</a>
					</li>
				</ul>
			</div>
		</div>
		

<style>
	.login{

		margin: 200px  100px;
	}
	.verifycode{
		cursor: pointer;
	}

</style>

<script>
	$(function(){
		/*验证码自动验证*/
		$('#verifycode').blur(function(){
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
		});
		$('#verifycode').focus(function(){
			$('#verifycode').parent().removeClass('has-error');
		});
		/*登录按钮*/
		$('#loginbutton').click(function(e){
			var _uid=$.trim($('#UserName').val());
			if(!_uid){
				$('#UserName').val('');
				$('#UserName').focus();
				return;
			}
			var _pwd=$('#Password').val();
			if(!$.trim(_pwd)){
				$('#Password').val('');
				$('#Password').focus();
				return;
			}
			var _isadmin=$('#isadmin').val();			
			/*记住我*/
			var _remember=parseInt($('#isremeber').val());
			var _code=$.trim($('#verifycode').val());
			var _status=parseInt($('#verifycode').attr('status'));
			if(!_code||!_status){
				$('#verifycode').attr('status',0);
				$('#verifycode').focus();
				return;
			}
			/**/
if(!_isadmin){
	_remember=_remember;
}else{
	_remember=0;
}
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

				}
			},'json');

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
		}
	});
</script>
<div class="container">
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
						<label>
							<?php if( ($admin != 1) AND ($admin != true)): ?><input id="rememberme" type="checkbox">记住我</label><?php endif; ?>
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