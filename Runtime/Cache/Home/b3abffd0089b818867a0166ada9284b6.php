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
$(document).ready(function () {
	/*刷新地址*/
	$('#refreshadd').click(function(e){
		var _url=$('#url').attr('appurl')+'/refreshadd';
		$.post(_url,{'':''},function(data){
			if(data.status==1){
				if(!data.info){return;}
				var _arr=$.parseJSON(data.info);
				if(!_arr){return;}
				$('#Address').children('option').remove();
				$(_arr).each(function(i,v){
					$('#Address').append("<option value="+v['Id']+" address="+v['address']+" >"+v['Tel']+"&nbsp;&nbsp;"+v['Address']+"</option>");
				});
				return;
			}
		});		
	});
</script>
<div class="container">
	<div class="text-center">
		<h1>购买商品</h1>
	</div>
	<br>

	<!-- 购买商品-->
    <form action="/Orange/Home/Goods/order2" method="post" enctype="multipart/form-data">
    	<input type="hidden" name="GoodsId" value="<?php echo ($info["Id"]); ?>" />
    	<input type="hidden" name="SellerId" value="<?php echo ($seller["Id"]); ?>" />
		<input type="hidden" name="SellerAddId" value="<?php echo ($info["AddressId"]); ?>" />
		<input type="hidden" name="E-Money" value="0" />
    	<div class="form-group">
			<label for="Title" class="col-sm-2 control-label">Title</label>
			<div class="col-sm-10">
				<input type="text" class="form-control " id="Title"
					value="<?php echo ($info["Title"]); ?>"  name="Title" Readonly>
			</div>
		</div>
		<div class="form-group">
			<label for="Price" class="col-sm-2 control-label">Price</label>
			<div class="col-sm-10">
				<input type="text" class="form-control " id="Price"
					value="<?php echo ($info["Price"]); ?>元" name="Price" Readonly>
			</div>
		</div>
		<div class="form-group">
			<label for="sellerNick" class="col-sm-2 control-label">sellerNick</label>
			<div class="col-sm-10">
				<input type="text" class="form-control " id="sellerNick"
					value="<?php echo ($seller["Nick"]); ?>" name="sellerNick" Readonly>
			</div>
		</div>
		<div class="form-group">
			<label for="sellerAddress" class="col-sm-2 control-label">sellerAddress</label>
			<div class="col-sm-10">
				<input type="text" class="form-control " id="sellerAddress"
					value="<?php echo ($cate["Address"]); ?>" name="sellerAddress" Readonly>
			</div>
		</div>
		<div class="form-group">
			<label for="sellerTel" class="col-sm-2 control-label">sellerTel</label>
			<div class="col-sm-10">
				<input type="text" class="form-control " id="sellerNick"
					value="<?php echo ($cate["Tel"]); ?>" name="Tel" Readonly>
			</div>
		</div>
		<div class="form-group">
			<label for="sellerQQ" class="col-sm-2 control-label">sellerQQ</label>
			<div class="col-sm-10">
				<input type="text" class="form-control " id="sellerQQ"
					value="<?php echo ($cate["QQ"]); ?>" name="QQ" Readonly>
			</div>
		</div>
		<div class="form-group">
			<label for="Address" class="col-sm-2 control-label">Address</label>
			<div class="col-sm-8">
				<select class="form-control " name="BuyerAddId" id="buyerAddress">
					<?php if(is_array($alist)): foreach($alist as $key=>$v): ?><option value="<?php echo ($v['Id']); ?>" address="<?php echo ($v['Address']); ?>"><?php echo ($v['Tel']); ?>&nbsp;&nbsp;<?php echo ($v['Address']); ?></option><?php endforeach; endif; ?>
				</select>
			</div>
			<div class="col-sm-2">
				<div class="pull-left">
					<a id="refreshadd" class="btn btn-default" data-toggle="tooltip"
						data-placement="top" title="刷新地址"> <span
						class="glyphicon glyphicon-refresh"></span>
					</a> <a href="<?php echo U('Usercenter/User/addaddress');?>" target="_blank"
						class="btn btn-default" data-toggle="tooltip" data-placement="top"
						title="添加地址"> <span class="glyphicon glyphicon-plus"></span>
					</a>
				</div>
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
				<button type="submit"  class="btn btn-default">购买</button>
				<a class="btn btn-default" href="javascript:history.go(-1)">返回</a>
			</div>
		</div>
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