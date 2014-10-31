<?php

namespace Usercenter\Model;

use Think\Model;

/**
 * 用户中心模型
 *
 */

class view_goods_order_listModel extends Model{
	/**
	 * 查询所有被自己购买的商品
	 */
	public function selectAllBuy($userid){
		$rst = $this -> where(array(
				'BuyerId' => $userid,
		 		'Status' => 10
		))->select();
		if ($rst) {
			$msg ['status'] = 1;
			$msg ['msg'] = $rst;
		}else {
			$mag['status'] = 0;
			$msg['msg'] = '网络繁忙，请稍后在试！';
		}
		return $msg;
	}

	/**
	 * 查询所有自己已出售的商品
	 */
	public function selectAllSell($userid){
		$rst = $this -> where(array(
				'SellerId' => $userid,
				'Status'=>10
		))->select();
		if ($rst) {
			$msg ['status'] = 1;
			$msg ['msg'] = $rst;
		}else {
			$mag['status'] = 0;
			$msg['msg'] = '网络繁忙，请稍后在试！';
		}
		return $msg;
	}
}