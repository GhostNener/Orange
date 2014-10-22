<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>商品详情</title>
</head>
<body>
<h1>商品详情</h1><br>
    <input type="hidden" name="GoodsId" value="<?php echo ($info["Id"]); ?>" />
    <label>名称：<?php echo ($info["Title"]); ?></label><br/>
    <label>价格：<?php echo ($info["Price"]); ?>元</label><br/>
    <label>原价：<?php echo ($info["CostPrice"]); ?>元</label><br/>
    <label>详情：<?php echo ($info["Presentation"]); ?></label><br/>
    <label>分类：<?php echo ($cate["Title"]); ?></label><br/>
    <label>地址：<?php echo ($info["AddressId"]); ?></label><br/>
    <label>浏览数：<?php echo ($info["Views"]); ?></label><br/>
    <label>收藏：<?php echo ($info["Collection"]); ?></label><br/>
    <button>购买</button>
    <p>评论</p><br>
    <table>
		<tr >
			<th class="text-center">评论人</th>
			<th class="text-center">内容</th>
			<th class="text-center">时间</th>
		</tr>
		<?php if(is_array($allComment)): foreach($allComment as $key=>$c): ?><tr>
				<td><?php echo ($c["UserNick"]); ?></td>
				<td><?php echo ($c["Content"]); ?></td>
				<td><?php echo (substr($c['CreateTime'],0,16)); ?></td>
			</tr><?php endforeach; endif; ?>
	</table><br/>
	<form action="/OrangeTS/Home/Goods/addComment" method="post" enctype="multipart/form-data">
		<input type="hidden" name="GoodsId" value="<?php echo ($info["Id"]); ?>" />
		<input type="hidden" name="UserId" value="1" />
		<textarea name="Content" rows="5" cols="30" ></textarea><br/>
		<input type="submit" value="我要评论">
	</form>
	
</body>
</html>