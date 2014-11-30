<?php
return array (
		// '配置项'=>'配置值'

		'DB_TYPE' => 'mysql',
		'DB_HOST' => '10.200.10.90',
		'DB_NAME' => 'juzidb',
		'DB_USER' => 'rootZ',
		'DB_PWD' => '8520', 
		/* 模块相关配置 */
		'DEFAULT_MODULE' => 'Home',
		'MODULE_DENY_LIST' => array (
				'Common' 
		),
		'MODULE_ALLOW_LIST' => array (
				'Home',
				'Api',
				'Admin',
				'Usercenter',
				'u',
				'user' 
		),
		'URL_MODULE_MAP' => array (
				'u' => 'Usercenter',
				'user' => 'Usercenter' 
		),
		
		'URL_ROUTER_ON' => true,
		/* 多级过滤  */
    	'DEFAULT_FILTER' => 'strip_tags,htmlspecialchars',
		'SESSION_AUTO_START' => true,
		/*url重写*/
		'URL_MODEL' => 2,
		/*url分隔符*/
		'URL_PATHINFO_DEPR' => '/',
		/*关闭自动模板布局*/
		'LAYOUT_ON' => false ,
		
		/*密码盐  */
		'PDW_SALT' => '91E0AF1A+B785-E497*F8C2/969E709AD1BA=',
		/*用户key持续时间 */
		'USER_KEY_EFFECTIVE' => 30 * 24 * 60 * 60 ,
		/*cookie前缀  */
		'COOKIE_PREFIX' => 'BIGORANGER_C',
		/*SESSION前缀  */
		'SESSION_PREFIX' => 'BIGORANGER_S',
		/*管理员所在组 name*/
		'ADMIN_ROLE_NAME' => 'Administrator',
		/* 普通用户用户组Id */
		'USER_ROLEID' => 2,
		/*记住我 cookie保存时间*/
		'COOKIE_REMEMBER_TIME' => 30 * 24 * 60 * 60,
		/*单日留言经验值上限  */
		'COMMENT_EXP_FOR_DAY' => 50,
		/*签到经验值增加上线  */
		'MAX_CLOCKIN_EXP' => 30,
		/*密码找回邮件过期时间  */
		'RESET_PWD_MAIL_TIME' => 24 * 60 * 60,
		/* 激活邮件配置 */
		'ORANGER_MAIL' => array (
				'SMTP_HOST' => 'smtp.ym.163.com', // SMTP服务器
				'SMTP_PORT' => 25, // SMTP服务器端口
				'SMTP_USER' => 'nener@bigoranger.com', // SMTP服务器用户名
				'SMTP_PASS' => 'ofofs1209', // SMTP服务器密码
				'FROM_EMAIL' => 'nener@bigoranger.com', // 发件人EMAIL
				'FROM_NAME' => '橘子团队', // 发件人名称
				'REPLY_EMAIL' => '', // 回复EMAIL（留空则为发件人EMAIL）
				'REPLY_NAME' => ''  // 回复名称（留空则为发件人名称）
				)
		,
		/*分类词典路径  【相对与ORG/phpAnalysis/】  */
		'CATEGOEY_DIC' => 'dict/category_dic_full.dic',
		/*全文检索词典路径  【相对与ORG/phpAnalysis/】  */
		'SEARCH_DIC' => 'dict/search_dic_full.dic' ,
		/*用户默认昵称前缀  */
		'RAND_NICK_PREFIX' => '',
		/*通知类型组  */
		'MSG_TYPE_GROUP' => array (
				'SYS' => '<span class="label label-danger">系统</span>',
				'ORDER' => '<span class="label label-success">订单</span>',
				'MSG' => '<span class="label label-info">留言</span>',
				'REPLY' => '<span class="label label-warning">回复</span>' 
		),
		/*通知标题  */
		'MSG_TYPE_TITLE_GROUP' => array (
				'MSG' => '你有一条新留言',
				'ORDER' => array (
						'SELL' => '商品已出售',
						'BUY' => '购买商品' 
				),
				'REPLY' => '你一有条回复信息',
				'SYS' => array (
						'GIFT' => '礼物兑换',
						'ACTIVITY' => '活动消息' 
				) 
		),
		/*通知模板  */
		'MSG_TYPE_CONTENT_PATH' => array (
				'MSG' => './Tpl/Public/Msg/msg.txt',
				'REPLY' => './Tpl/Public/Msg/reply.txt',
				'ORDER' => array (
						'BUY' => './Tpl/Public/Msg/order_buy.txt',
						'SELL' => './Tpl/Public/Msg/order_sell.txt' 
				) 
		),
		/*通知模板渲染占位符  */
		'MSG_TPL_PLACEHOLDER' => array (
				'Title' => '[$_Title_$]',
				'GURL' => '[$_GURL_$]',
				'UURL' => '[$_UserURL_$]',
				'Nick' => '[$_Nick_$]',
				'Content' => '[$_Content_$]',
				'CId' => '[$_CId_$]',
				'AId' => '[$_AId_$]',
				'GId' => '[$_GId_$]',
				'Tel' => '[$_Tel_$]' 
		),
		/* 商品评论最大返回数量 */
		'COMMENTS_LIST_COUNT' => 50,
		/*商品置顶服务类型 */
		'TOP_SERVICE_TYPE' => 888,
		/*商品订单session 缓存值  */
		'GOODS_ORDER_SESSION_VALUE' => 1024 * 1024 * 9,
		'SEARCH_CATEGORY_NAME' => '搜索关键字',
		/*发布时最多收取发布费用！不算服务费  */
		'MAX_PUBLISH_COST' => 10,
		/*发布收费百分比  */
		'PUBLISH_COST_PERCENT' => 0.00,
		/*激活邮件模板路径  */
		'ACTIVE_MAIL_TPL_PATH' => './Tpl/Public/Mail/activemail.txt',
		/*密码找回邮件模板  */
		'FIND_PWD_MAIL_TPL_PATH' => './Tpl/Public/Mail/findpwd.txt',
		/*帐号绑定邮件模板  */
		'BUND_MAIL_TPL_PATH' => './Tpl/Public/Mail/bundling.txt',
		/*定制成功以及错误模板  */
		'TMPL_ACTION_ERROR' => './Tpl/jump.html',
		'TMPL_ACTION_SUCCESS' => './Tpl/jump.html',
		/*七牛OSS*/
		'FILE_SIZE_SEPARATOR' => '-', // 分隔符
		'UPLOAD_SITEIMG_QINIU' => array (
				'maxSize' => 6 * 1024 * 1024, // 文件大小
				'rootPath' => './',
				'savePath' => 'Activity/',
				'autoSub' => false,
				'saveName' => str_replace ( '.', '', microtime ( true ) ),
				'driver' => 'Qiniu',
				'exts' => array (
						'jpg',
						'gif',
						'png',
						'jpeg' 
				),
				'driverConfig' => array (
						'secrectKey' => '-LdkxeAxW_or_1UssZbSdATJmlVZm5G-M4oWRDcD', // 'ApFyYUL4aaaPbGAYQRJT1-4qGUJfjziQ7KQ_SJni',
						'accessKey' => 'fJOIxQXMh6cn0j0FNSsx4uSEwG9sFCkel0BhwdOw', // 'dU131Y7XxO1dZtrPNWFg2RCW1PbemyoVPecdldP_',
						'domain' => 'bigoranger.qiniudn.com', // 'ghostdatabase.qiniudn.com',
						'bucket' => 'bigoranger', // 'bigoranger',
						'CallbackUrl' => '',
						'CallbackBody' => 'key=$(key)&cid=$(x:cid)&sid=$(x:sid)&ckey=$(x:ckey)',
						'Expires' => 36000 
				) 
		) 
);

