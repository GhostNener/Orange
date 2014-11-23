<?php
namespace Usercenter\Model;
use Think\Model;
class goods_orderModel extends Model {
	/**
	 * 修改账单状态
	 * 
	 * @param $goodsId 账单Id,$Type 类型 1.发货 2. 收货
	 * @return array status, msg
	 */
	public function update( $orderId, $Type ){
		if ($Type == 1) {
			$rst = $this->where( array(
					'Id' => $orderId,
					'SellerId' => cookie('_uid'),
					'Status' => 10
			) ) -> save( array(
					'Status' => 21
			) );
			if ($rst) {
				return array (
						'status' => 1,
						'msg' => "发货成功" 
				);
			} else {
				return array (
						'status' => 0,
						'msg' => "发货失败" 
				);
			};
		}elseif ($Type == 2){
			$rst = $this->where( array(
					'Id' => $orderId,
					'BuyerId' => cookie('_uid'),
					'Status' => 21
			) ) -> save( array(
					'Status' => 22
			));
			if ($rst) {
				return array (
						'status' => 1,
						'msg' => "收货成功" 
				);
			} else {
				return array (
						'status' => 0,
						'msg' => "收货失败" 
				);
			};
		}
	}
	
	/**
	 * 将交易双方都评价过的 变为结束交易
	 * Enter description here ...
	 */
	public function isComplete(){
		$cond['BuyerStar'] = array('NEQ','NULL');
		$cond['SellerStar'] = array('NEQ','NULL');
		$rst = $this -> where($cond) -> save(array('Status' => 25));
	}
	
	/**
	 * 修改订单Star
	 * @param $goodsId 账单Id,$Type 类型 1.卖家评价  2.买家评价
	 * @return array status, msg
	 */
	public function savestar( $orderId, $Type, $Star ){
		if ($Type == 1) {
			$rst = $this->where( array(
					'Id' => $orderId,
					'SellerId' => cookie('_uid'),
					'Status' => 22
			) ) -> save( array(
					'BuyerStar' => $Star
			) );
			if ($rst) {
				return array (
						'status' => 1,
						'msg' => "评价成功" 
				);
			} else {
				return array (
						'status' => 0,
						'msg' => "评价失败" 
				);
			};
		}elseif ($Type == 2){
			$rst = $this->where( array(
					'Id' => $orderId,
					'BuyerId' => cookie('_uid'),
					'Status' => 22
			) ) -> save( array(
					'SellerStar' => $Star
			));
			if ($rst) {
				return array (
						'status' => 1,
						'msg' => "评价成功" 
				);
			} else {
				return array (
						'status' => 0,
						'msg' => "评价失败" 
				);
			};
		}
	}
}