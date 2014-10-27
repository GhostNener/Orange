<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>商品详情</title>
</head>
<body>
<h1>商品详情</h1><br>
    <input type="hidden" name="GoodsId" value="<?php echo ($model[0]["Id"]); ?>" />
    <label>名称：<?php echo ($model[0]["Title"]); ?></label><br/>
    <label>价格：<?php echo ($model[0]["Price"]); ?>元</label><br/>
    <label>原价：<?php echo ($model[0]["CostPrice"]); ?>元</label><br/>
    <label>详情：<?php echo ($model[0]["Presentation"]); ?></label><br/>
    <label>浏览数：<?php echo ($model[0]["Views"]); ?></label><br/>
    <label>收藏：<?php echo ($model[0]["Collection"]); ?></label><br/>
    <?php if(is_array($model)): foreach($model as $key=>$img): ?><img alt="" src="/Orange<?php echo ($img["imgURL"]); ?>"/><?php endforeach; endif; ?>
    <a href="<?php echo U('Goods/order',array('Id'=>$model[0]['Id']));?>">购买</a>
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
	<form action="/Orange/Home/Goods/addComment" method="post" enctype="multipart/form-data">
		<input type="hidden" name="GoodsId" value="<?php echo ($model[0]["Id"]); ?>" />
		<input type="hidden" name="UserId" value="1" />
		<textarea name="Content" rows="10" cols="50" ></textarea><br/>
		<input type="submit" value="我要评论">
	</form>
	
</body>
</html>