<?php

namespace Home\Model;

use Think\Model;

/**
 * 商品服务模型
 *
 * @author NENER
 *        
 */
class goods_serviceModel extends Model {
	
	/**
	 * 获取服务
	 *
	 * @param int $userid：用户Id        	
	 * @return array ：所有符合的地址列表
	 */
	public function getall() {
		$arr = M ( 'goods_service' )->where ( array (
				'Status' => 10 
		) )->select ();
		return $arr;
	}
}

?>