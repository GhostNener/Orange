<?php
return array (
		'URL_ROUTE_RULES' => array (
				'add$' => 'Home/Goods/add', // 发布
				'g/:id' => 'Home/Index/g_show?Id=:1', // 商品详情
				'gift$' => 'Home/Gift/index', // 礼物
				'find$' => 'Home/Activity/index', // 发现
				's' => 'Home/Index/searchgoods?wd=:1', // 搜索
				'c/:id' => 'Home/Index/cggoods?Id=:1', // 分类
				'f/:id' => 'Home/Activity/detail?id=:1', // 发现
				'o$' => 'Home/Order/index',  // 订单
				'buysuccess$'=>'Home/Order/createorder',
				'lottery$'=>'Home/Prize/index',//抽奖
				'collect$'=>'Home/Index/collect',//头衔征集
				'recharge$'=>'Home/Prize/recharge'//代金券兑换
		     )
		 
);