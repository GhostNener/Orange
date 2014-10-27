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
		
<script type="text/javascript">
function jump(userId){
	document.getElementById("AssesseeId").value=userId;
	document.getElementById("commentt").focus();
}
</script>
<<style>
#com a:link {color:green} 
#com a:hover {color:gray;text-decoration:underline;}
</style>
<div class="container">
	<div class="text-center">
		<h1>商品详情</h1>
	</div>
	<br>

	<!-- 购买商品-->
    <input type="hidden" name="GoodsId" value="<?php echo ($model[0]["Id"]); ?>" />
    <div class="form-group">
    <label class="col-sm-2 control-label">名称：<?php echo ($model[0]["Title"]); ?></label></div><br/>
    <div class="form-group">
    <label class="col-sm-2 control-label">价格：<?php echo ($model[0]["Price"]); ?>元</label></div><br/>
    <div class="form-group">
    <label class="col-sm-2 control-label">原价：<?php echo ($model[0]["CostPrice"]); ?>元</label></div><br/>
    <div class="form-group">
    <label class="col-sm-2 control-label">详情：<?php echo ($model[0]["Presentation"]); ?></label></div><br/>
    <div class="form-group">
    <label class="col-sm-2 control-label">浏览数：<?php echo ($model[0]["Views"]); ?></label></div><br/>
    <div class="form-group">
    <label class="col-sm-2 control-label">收藏：<?php echo ($model[0]["Collection"]); ?></label></div><br/>
    <div class="form-group">
    <a href="<?php echo U('Goods/order',array('Id'=>$model[0]['Id']));?>" class="btn btn-default">购买</a>
    <a class="btn btn-default" href="javascript:history.go(-1)">返回</a>
    </div>
    <?php if(is_array($model)): foreach($model as $key=>$img): ?><img alt="" src="/Orange<?php echo ($img["imgURL"]); ?>"/><br/><?php endforeach; endif; ?>
    <br>
    <p>评论</p><hr style="border-color: black;">
    <ul id="com">
		<?php if(is_array($allComment)): foreach($allComment as $key=>$c): ?><li style="list-style: none;"><?php echo ($c["UserNick"]); ?>&nbsp;<a href="#" ><?php if((($c['UserId'] == $c['AssesseeId']) or ($c['AssesseeId'] == null))): else: ?>@<?php echo ($c["AN"]); endif; ?></a>&nbsp;
				<?php echo ($c["Content"]); ?>&nbsp;&nbsp;<?php echo (substr($c['CreateTime'],0,16)); ?>
				&nbsp;&nbsp;&nbsp;<a href="#Content" onclick="javascript:jump(<?php echo ($c["UserId"]); ?>)" >回复</a>
				</li>
				<hr style="border-color: blue;"><?php endforeach; endif; ?>
	</ul>
	<form action="/Orange/Home/Goods/addComment" method="post" enctype="multipart/form-data">
		<input type="hidden" name="GoodsId" value="<?php echo ($model[0]["Id"]); ?>" />
		<input type="hidden" name="AssesseeId" id="AssesseeId" value="" />
		<input type="hidden" name="UserId" value="1" />
		<textarea name="Content" rows="10" cols="50" id="commentt"></textarea><br/>
		<input type="submit" value="我要评论">
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