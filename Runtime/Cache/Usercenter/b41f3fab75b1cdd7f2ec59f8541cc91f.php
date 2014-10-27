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
		$('#Nick').focus(function(){
			removemsg('Nick');
		});
		$('#UserName').focus(function(){
			removemsg('UserName');
		});		
		$('#Password').focus(function(){
			removemsg('Password');
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
			var ispwd=/^[a-z0-9_]{6,18}$/;/*/^(?!\D+$)(?!\d+$)[a-zA-Z0-9_]\w{6,16}$/*/;
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

<div class="container">
	<div class="login">
		<h1 class="text-center">新用户</h1>
		<br>
		<form class="form-horizontal" role="form" action="" method="post">
			<div class="form-group">
				<label for="Nick" class="col-sm-2 control-label">昵称</label>
				<div class="col-sm-8 ">
					<input type="text" class="form-control" id="Nick" placeholder="昵称"></div>
			</div>
			<div class="form-group">
				<label for="UserName" class="col-sm-2 control-label">用户名</label>
				<div class="col-sm-8">
					<input type="email" class="form-control" id="UserName" placeholder="邮箱"></div>
			</div>
			<div class="form-group">
				<label for="Password" class="col-sm-2 control-label">密码</label>
				<div class="col-sm-8">
					<input type="password" class="form-control" id="Password" placeholder="密码"></div>
			</div>
			<div class="form-group">
				<label for="confirmPassword" class="col-sm-2 control-label">确认密码</label>
				<div class="col-sm-8">
					<input type="password" class="form-control" id="confirmPassword" placeholder="确认密码"></div>
			</div>
			<input type="hidden" id="url" value="0"  regist="<?php echo U('Usercenter/User/signup');?>" home="<?php echo U('Home/Index/index');?>" >
			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-8">
					<button type="button" id="registbutton" class="btn btn-default">提交</button>
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