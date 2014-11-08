<?php

namespace Usercenter\Model;

use Think\Model;

/**
 * 订单模型
 *
 */

class view_goods_order_listModel extends Model{
	/**
	 * 查询订单及商品
	 */
	public function getorder($wherearr){
		$rst = $this -> where( $wherearr )->select();
		if ($rst) {
			$msg ['status'] = 1;
			$msg ['msg'] = $rst;
		}else {
			$mag['status'] = 0;
		}
		return $msg;
	}
}