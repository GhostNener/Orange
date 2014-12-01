<?php
return array (
		'URL_ROUTE_RULES' => array (
				'act$' => 'u/Index/activated', // 激活
				'msg' => 'u/Index/msg',
				'edit$' => 'u/Index/edit',
				'order' => 'u/Index/order',
				'sell' => 'u/Index/sell',
				'f' => 'u/Index/follow',
				'like' => 'u/Index/like',
				'login$' => 'u/User/u_login',
				'regist$' => 'u/User/regist',
				'logout$' => 'u/User/logout',
				'pay$' => 'u/Pay/index',
				'bundlmail/:key/:mail$'=>'u/Index/bundlmail?key=:1&mail=:2',
				'pcode$' => 'u/Pay/qrcode',
				'lostpwd$' => 'u/User/lostpwd',
				'lostpaypwd$' => 'u/Index/lostpaypwd',
				'resetpwd/:key' => 'u/User/resetpwd?key=:1',
				'resetpaypwd/:key' => 'u/Index/resetpaypwd?key=:1',
				'myprize$'=>'u/Index/prize',
				':Id$' => 'u/User/u_show?Id=:1' 
		),
		/*支付宝接口  */
		'ALIPAY' => array (
				'SUBMIT' => array (
						// 加密key，开通支付宝账户后给予
						'key' => 'kbutlg03l4u2anhkgk5frao0jjlyxr7h',
						// 合作者ID，支付宝有该配置，开通易宝账户后给予
						'partner' => '2088712129704087',
						'sign_type' => strtoupper ( 'MD5' ),
						'input_charset' => strtolower ( 'utf-8' ),
						'transport' => 'http',
						'cacert' => '' 
				),
				'PARAM' => array (
						"service" => "trade_create_by_buyer",
						"partner" => '2088712129704087',
						"payment_type" => '1',
						"notify_url" => 'http://bigoranger.com/u/Public/handlealipay', // 回调地址
						"return_url" => 'http://bigoranger.com/u/Pay/handlealipay', // 跳转地址
						"seller_email" => 'leecinwell@qq.com', // 收款人
						"out_trade_no" => '', // 订单号
						"subject" => '大橘子', // 单位名称
						"price" => 0, // 付款金额
						"quantity" => '1', // 商品数量
						"logistics_fee" => '0.00', // 邮费
						"logistics_type" => 'EXPRESS', // 快递类型
						"logistics_payment" => 'SELLER_PAY', // 担保方式
						"body" => '',
						"show_url" => '',
						"receive_name" => '',
						"receive_address" => '',
						"receive_zip" => '',
						"receive_phone" => '',
						"receive_mobile" => '',
						"_input_charset" => strtolower ( 'utf-8' ) 
				) 
		) 
)
;