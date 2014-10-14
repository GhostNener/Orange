<?php
return array(
	//'配置项'=>'配置值'
		'DB_TYPE'               =>  'mysql',     // 数据库类型
		'DB_HOST'               =>  '10.200.10.90', // 服务器地址
		'DB_NAME'               =>  'juzidb',          // 数据库名
		'DB_USER'               =>  'rootZ',      // 用户名
		'DB_PWD'                =>  '8520',          // 密码
				//'DB_USER'               =>  'zhoumeng',      // 用户名
		//'DB_PWD'                =>  '8520',          // 密码
		/* 模块相关配置 */
		'DEFAULT_MODULE'     => 'Home',
		'MODULE_DENY_LIST'   => array('Common'),
		'MODULE_ALLOW_LIST'  => array('Home','Admin'),
		/*多级过滤  */
		'DEFAULT_FILTER'        => 'strip_tags,htmlspecialchars',
);
