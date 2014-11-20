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
			array ('Time',NOW_TIME),
			array ('UserId','getUserId',1,'callback'),
			array ('UserName','getUserName',1,'callback'),
			array ('IP','get_client_ip',1,'function'),
			array ('Type','getType',1,'callback')
	);

	protected function getUserId() {
		return cookie('_uid');
	}

	protected function getUserName() {
		return base64_decode(cookie('_uname'));
	}

	protected function getType($type){
		switch ($type) {
			
			case '1':
				return 'goods';
				break;

			case '2':
				return 'user';
				break;

			case '3':
				return 'order';
				break;

			case '4':
				return 'pay';
				break;

			default:
				return 'other';
				break;
		}
	}

	/**
	 * 添加一条日志
	 *
	 * @param string $contents
	 *        	内容
	 * @param int $type
	 *        	1: 商品（发布、购买、下架等），2: 用户行为（登录、修改密码、注册、激活），3: 订单（下单、完成订单），4: 充值, 5: 其他
	 */
	public function log($contents, $type=5) {

		$data['Action'] = $contents;
		$data['Type'] = $type;
		$result = $this->create($data);
		if($result){
			$this->add($result);
		}
	}

}