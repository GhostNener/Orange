<?php
return array(
			'URL_ROUTE_RULES' => array (
				'act$'=>'u/Index/activated'	,//激活
				'msg$'=>	'u/Index/msg',
				'edit$'=>'u/Index/edit',
				'order$'=>'u/Index/order',
				'sell$'=>'u/Index/sell',
				'f$'=>'u/Index/follow',
				'like$'=>'u/Index/like',
				'login$'=>'u/User/u_login',
				'regist$'=>'u/User/regist',
				'logout$'=>'u/User/logout',
				'pay$'=>'u/Pay/index',
				'pcode$'=>'u/Pay/qrcode',
				':Id$'=>'u/User/u_show?Id=:1'
		) 
) 
;