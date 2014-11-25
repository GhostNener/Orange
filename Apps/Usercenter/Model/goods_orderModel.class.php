<?php
namespace Usercenter\Model;
use Think\Model;
class goods_orderModel extends Model {
	/**
	 * 修改账单状态
	 * 
	 * @param $goodsId 账单Id
	 * @param $Type 类型
	 * 			 1.发货 2. 收货
	 * @return array
	 * @author LongG
	 */
	public function update( $orderId, $Type ){
		switch ($Type){
			case "1":
				$wherearr = array(
						'Id' => $orderId,
						'SellerId' => cookie('_uid'),
						'Status' => 10
				);
				$where = array( 'Status' => 21 );
				break;
			case "2":
				$wherearr = array(
						'Id' => $orderId,
						'BuyerId' => cookie('_uid'),
						'Status' => 21
				);
				$where = array( 'Status' => 22 );
				break;
			default:
				return "";
				break;
		}
		$rst = $this->where( $wherearr )->save( $where );
		if (!$rst) {
			return array (
					'status' => 0,
					'msg' => "操作失败" 
			);
		} else {
			return array (
					'status' => 1,
					'msg' => "操作成功" 
			);
		};
	}
	
	/**
	 * 将交易双方都评价过的 变为结束交易
	 * @author LongG
	 */
	public function isComplete(){
		$cond['BuyerStar'] = array('NEQ','NULL');
		$cond['SellerStar'] = array('NEQ','NULL');
		$rst = $this -> where($cond) -> save(array('Status' => 25));
	}
	
	/**
	 * 评价 操作
	 * @param $goodsId 账单Id,
	 * @param $Type 类型
	 * 		 1.卖家评价  2.买家评价
	 * @param $Star star 数
	 * @return array
	 * @author LongG
	 */
	public function savestar( $orderId, $Type =1, $Star ){
		switch ($Type){
			case "1":
				$wherearr = array(
						'Id' => $orderId,
						'SellerId' => cookie('_uid'),
						'Status' => 22
				);
				$where = array( 'BuyerStar' => $Star );
				break;
			case "2":
				$wherearr = array(
						'Id' => $orderId,
						'BuyerId' => cookie('_uid'),
						'Status' => 22
				);
				$where = array( 'SellerStar' => $Star );
				break;
			default:
				return "";
				break;
		}
		$rst = $this->where( $wherearr )->save( $where );
		if (!$rst) {
			return array (
					'status' => 0,
					'msg' => "评价失败" 
			);
		} else {
			return array (
					'status' => 1,
					'msg' => "评价成功" 
			);
		};
	}
}