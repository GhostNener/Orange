<?php

namespace Home\Model;

use Think\Model;

/**
 * 用户地址模型
 *
 * @author NENER
 *        
 */
class user_addressModel extends Model {
	
	/**
	 * 获取地址
	 *
	 * @param int $userid：用户Id        	
	 * @return array ：所有符合的地址列表
	 */
	public function getall($userid) {
		$arr = M ( 'user_address' )->order ( 'IsDefault DESC' )->where ( array (
				'Status' => 10,
				'UserId' => $userid 
		) )->select ();
		return $arr;
	}
}

?>