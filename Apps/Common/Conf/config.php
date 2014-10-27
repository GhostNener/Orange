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
				'Usercenter' 
		),
				/* 多级过滤  */
    'DEFAULT_FILTER' => 'strip_tags,htmlspecialchars',
				/*url重写*/
		'URL_MODEL' => 2,
				/*url分隔符*/
		'URL_PATHINFO_DEPR' => '/',
				/*图片上传配置*/
		'IMG_UPLOAD_CONFIG' => array (
				'maxSize' => 5 * 1024 * 1024,
				'rootPath' => './Uploads/',
				'savePath' => 'GoodsImg/',
				'saveName' => str_replace ( '.', '', microtime ( true ) ),
				'exts' => array (
						'jpg',
						'gif',
						'png',
						'jpeg' 
				),
				'autoSub' => false,
				'hash' => false 
		)
		 ,
						/*文件上传目录*/
		'UPLOADS_FOLDER' => __ROOT__ . "/Uploads",
						/*商品图像上传主目录*/
		'GOODS_IMG_ROOT' => './Uploads/GoodsImg/',
						/*原图*/
		'GOODS_IMG_SOURCE' => 'Source/',
						/*最大800*800*/
		'GOODS_IMG_800' => '800_800/',
						/*缩略图100*100*/
		'GOODS_IMG_100' => '100_100/',
						/*商品图片 缩略图   分辨率*/
		'GOODS_IMG_THUMB' => array (
				100,/*最大宽度  */
				100 
		),
						/*商品图片 正常图片最大 分辨率 */
		'GOODS_IMG_MD' => array (
				800,
				800 
		),
						/*关闭自动模板布局*/
		'LAYOUT_ON' => false ,
		
		/*密码盐  */
		'PDW_SALT' => '91E0AF1A+B785-E497*F8C2/969E709AD1BA=',
		/*用户key持续时间
		 * */
		'USER_KEY_EFFECTIVE' => 30 * 24 * 60 * 60 ,
		/*cookie前缀  */
		'COOKIE_PREFIX' => 'ORANGER',
		/*管理员所在组 name*/
		'ADMIN_ROLE_NAME' => 'Administratoe',
		/*记住我 cookie保存时间*/
		'COOKIE_REMEMBER_TIME' => 30 * 24 * 60 * 60 ,
		/* 普通用户用户组Id */
		'USER_ROLEID' => 2,
		/*  */
		'ORANGER_MAIL' => array (
				'SMTP_HOST' => 'smtp.ym.163.com', // SMTP服务器
				'SMTP_PORT' => 25, // SMTP服务器端口
				'SMTP_USER' => 'nener@bigoranger.com', // SMTP服务器用户名
				'SMTP_PASS' => 'ofofs1209', // SMTP服务器密码
				'FROM_EMAIL' => 'nener@bigoranger.com', // 发件人EMAIL
				'FROM_NAME' => '橘子团队', // 发件人名称
				'REPLY_EMAIL' => '', // 回复EMAIL（留空则为发件人EMAIL）
				'REPLY_NAME' => ''  // 回复名称（留空则为发件人名称）
				),
		/*图片保存类型  */
		'IMG_SAVE_TYPE' => 'jpg',
		/*图片保存质量  */
		'IMG_SAVE_QUALITY' => 90 
);
