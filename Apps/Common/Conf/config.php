<?php
return array (
		// '配置项'=>'配置值'
		/*'DB_TYPE' => 'mysql', // 数据库类型
		'DB_HOST' => '10.200.10.90', // 服务器地址
		'DB_NAME' => 'juzidb', // 数据库名
		'DB_USER' => 'rootZ', // 用户名
		'DB_PWD' => '8520', // 密码
		*/'DB_TYPE' => 'mysql',
		 'DB_HOST' => '127.0.0.1', 
		  'DB_NAME' => 'juzi',
		   'DB_USER' => 'zhoumeng', 
		    'DB_PWD' => '8520', 
		    
		 
		/* 模块相关配置 */
		'DEFAULT_MODULE' => 'Home',
		'MODULE_DENY_LIST' => array (
				'Common' 
		),
		'MODULE_ALLOW_LIST' => array (
				'Home',
				'Admin' 
		),
    /* 多级过滤  */
    'DEFAULT_FILTER' => 'strip_tags,htmlspecialchars',
		 /*url重写*/
		'URL_MODEL' => 2,
		 /*url分隔符*/
		'URL_PATHINFO_DEPR' => '/',
		// 图片上传配置
		'IMG_UPLOAD_CONFIG' => array (
				'maxSize' => 5242880,
				'rootPath' => 'Uploads',
				'savePath' => '/GoodsImg/',
				'saveName' => str_replace ( '.', '', microtime ( true ) ),
				'exts' => array (
						'jpg',
						'gif',
						'png',
						'jpeg' 
				),
				'autoSub' => false,
				'hash' => false 
		) ,
		/*文件上传目录*/
		'UPLOADS_FOLDER'=>__ROOT__."/Uploads",
		/*商品图像上传主目录*/
		'GOODS_IMG_ROOT'=>'Uploads/GoodsImg/',
		/*原图*/
		'GOODS_IMG_SOURCE'=>'Source/',
		/*最大800*800*/
		'GOODS_IMG_800'=>'800_800/',
		/*缩略图100*100*/
		'GOODS_IMG_100'=>'100_100/',

		/*关闭自动模板布局*/
		'LAYOUT_ON'=>false
);
