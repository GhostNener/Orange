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
	public function update( $orderId, $Type=1 ){
		$Type= (int)$Type;
		switch ($Type){
			case 1:
				$wherearr = array(
						'Id' => $orderId,
						'SellerId' => cookie('_uid'),
						'Status' => 10
				);
				$where = array( 'Status' => 21 );
				break;
			case 2:
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
	 * 评价 操作
	 * @param $goodsId 账单Id,
	 * @param $Star star 数
	 * @return array
	 * @author LongG
	 */
	public function savestar( $oid, $Star ){
		$msg = $this->where( array( 'Id' =>$oid ) )-> find();
		$uid = cookie('_uid');
		if (!$msg ) {
			return array (
					'status' => 0,
					'msg' => "评价失败" 
			);
		}
		if ($msg['BuyerId']==$uid) {
			$wherearr = array( 'SellerStar' => $Star, 'IsBuyEvaluate' => 1 );
		} else {
			$wherearr = array( 'BuyerStar' => $Star, 'IsSellEvaluate' => 1 );
		}
		$rst = $this->where( array( 'Id' => $oid ) )->data( $wherearr ) -> save();
		if (!$rst) {
			return array (
					'status' => 0,
					'msg' => "评价失败2" 
			);
		} else {
			return array (
					'status' => 1,
					'msg' => "评价成功" 
			);
		}
		
	}
}