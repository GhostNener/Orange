<?php

namespace Home\Model;

use Think\Model;
use Usercenter\Model\userModel;
use Usercenter\Model\user_addressModel;

/**
 * 订单模型
 *
 * @author DongZ
 *        
 */
class goods_orderModel extends Model {
	
	/**
	 * 自动验证
	 *
	 * @var unknown
	 */
	protected $_validate = array (
			array (
					'GoodsId',
					'checkgoods',
					'商品已下架或不存在',
					self::MUST_VALIDATE,
					'callback' 
			),
			array (
					'GoodsId',
					'',
					'商品已下架或不存在',
					self::MUST_VALIDATE,
					'unique' 
			) 
	);
	/**
	 * 自动校验商品
	 *
	 * @param int $id        	
	 */
	protected function checkgoods($id) {
		$m = new goodsModel ();
		$model = $m->findone ( $id );
		if (! $model) {
			return false;
		}
	}
	/**
	 * 创建一个订单
	 *
	 * @param array $data
	 *        	:GoodsId,Code,CreateTime,BuyerAddId,TradeWay
	 * @param array $uid
	 *        	用户id
	 * @return array ：status，msg ，address
	 * @author NENER
	 *        
	 */
	public function createone($data, $uid = -1) {
		$msg ['status'] = 0;
		$m = new goodsModel ();
		$model = $m->findone ( $data ['GoodsId'] );
		if (! $model) {
			$msg ['msg'] = '商品已下架或不存在';
			return $msg;
		}
		$waylist = createtradeway ( $model ['TradeWay'], 2 );
		if (! in_array ( $data ['TradeWay'], $waylist )) {
			$msg ['msg'] = '交易方式不合法';
			return $msg;
		}
		if (! $uid || $uid == - 1) {
			$data ['BuyerId'] = cookie ( '_uid' );
		} else {
			$data ['BuyerId'] = $uid;
		}
		$data ['Price'] = $model ['Price'];
		$data ['SellerId'] = $model ['UserId'];
		$data ['SellerAddId'] = $model ['AddressId'];
		$data ['Status'] = 10;
		/* 创建模型 */
		$order = $this->create ( $data );
		if (! $order) {
			$msg ['msg'] = $this->getError ();
			return $msg;
		}
		$dal = M ();
		$dal->startTrans ();
		/* 如果是线上交易 冻结对应的电子货币 */
		if (( int ) $data ['TradeWay'] == 1) {
			$um = new userModel ();
			$r1 = $um->payEM ( $data ['BuyerId'], ( int ) $data ['Price'] );
		} else {
			$r1 = true;
		}
		if (! $r1) {
			$msg ['msg'] = '余额不足';
			$dal->rollback ();
			return $msg;
		}
		/* 进行商品冻结 */
		$r2 = $m->freeze ( $data ['GoodsId'], 1 );
		/* 保存订单 */
		$r3 = $this->add ( $order );
		/* 对事务进行处理 */
		if (! $r2 || ! $r3) {
			$msg ['msg'] = '对不起！购买失败，请重试！';
			$dal->rollback ();
			return $msg;
		} else {
			$dal->commit ();
			$am = new user_addressModel ();
			$address = $am->getbyid ( $data ['SellerAddId'] );
			return array (
					'status' => 1,
					'msg' => '交易生效，请与卖家联系',
					'address' => $address 
			);
		}
	}
}