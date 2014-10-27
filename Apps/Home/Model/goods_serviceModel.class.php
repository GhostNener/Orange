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
	 *@author NENER
	 * @param int $userid：用户Id        	
	 * @return array ：所有符合的地址列表
	 */
	public function getall() {
		$arr =$this->where ( array (
				'Status' => 10 
		) )->select ();
		return $arr;
	}
}

?>