<?php

namespace Home\Model;

use Think\Model;

/**
 * 消息模块
 *
 * @author Cinwell
 *        
 */
class logsModel extends Model {
	
	/**
	 * 自动完成
	 *
	 * @var unknown
	 * @author Cinwell
	 */
	protected $_auto = array (
		//IP接口http://whois.pconline.com.cn/
			array (
				'Time',
				NOW_TIME,
				self::MODEL_INSERT 
			),
			array (
				'UserId',
				cookie('_uid'),
				slef::MODEL_INSERT
			),
			array (
				'UserId',
				base64_decode(cookie('_uname')),
				slef::MODEL_INSERT
			),
			array (
				'IP',
				get_client_ip(),
				slef::MODEL_INSERT
			),

	);

	public function Add($value='')
	{
		# code...
	}

}