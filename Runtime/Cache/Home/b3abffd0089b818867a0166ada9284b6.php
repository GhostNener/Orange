<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>购买商品</title>
</head>
<body>
<h1>购买商品</h1>
<form action="/OrangeTS/Home/Goods/order2" method="post"
	enctype="multipart/form-data"><input type="hidden"
	name="GoodsId" value="<?php echo ($info["Id"]); ?>" /> <input type="hidden"
	name="BuyerId" value="3" /> <input type="hidden" name="SellerId"
	value="<?php echo ($userName["Id"]); ?>" /> <input type="hidden" name="SellerAddId"
	value="<?php echo ($info["AddressId"]); ?>" /> <input type="hidden" name="E-Money"
	value="0" />
<table>
	<tr>
		<td>名称：</td>
		<td><?php echo ($info["Title"]); ?></td>
	</tr>
	<tr>
		<td>价格：</td>
		<td><input type="text" readonly="readonly" name="Price"
			value="<?php echo ($info["Price"]); ?>" />元</td>
	</tr>
	<tr>
		<td>卖家名称：</td>
		<td><?php echo ($user["Nick"]); ?></td>
	</tr>
	<tr>
		<td>卖家地址：</td>
		<td><input type="text" readonly="readonly" name="Address"
			value="<?php echo ($cate["Address"]); ?>" /></td>
	</tr>
	<tr>
		<td>卖家电话：</td>
		<td><input type="text" readonly="readonly" name="Tel"
			value="<?php echo ($cate["Tel"]); ?>" /></td>
	</tr>
	<tr>
		<td>卖家QQ：</td>
		<td><input type="text" readonly="readonly" name="QQ"
			value="<?php echo ($cate["QQ"]); ?>" /></td>
	</tr>
	<tr>
		<div class="form-group"><label for="Address"
			class="col-sm-2 control-label">收货地址</label>
		<div class="col-sm-8"><select class="form-control "
			name="Address" id="Address">
			<?php if(is_array($alist)): foreach($alist as $key=>$v): ?><option value="<?php echo ($v['Id']); ?>" address="<?php echo ($v['Address']); ?>"><?php echo ($v['Tel']); ?>&nbsp;&nbsp;<?php echo ($v['Address']); ?></option><?php endforeach; endif; ?>
		</select></div>
		<div class="col-sm-2">
		<div class="pull-left"><a id="refreshadd"
			class="btn btn-default" data-toggle="tooltip" data-placement="top"
			title="刷新地址"> <span class="glyphicon glyphicon-refresh"></span> </a>
		<a href="<?php echo U('User/addaddress');?>" target="_blank"
			class="btn btn-default" data-toggle="tooltip" data-placement="top"
			title="添加地址"> <span class="glyphicon glyphicon-plus"></span> </a></div>
		</div>
		</div>
	</tr>
</table>
<input type="submit" value="购买"></form>
</body>
</html>