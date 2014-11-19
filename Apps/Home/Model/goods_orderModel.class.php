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
	 * 创建通知
	 * Title，Content，SendId，RecipientId
	 *
	 * @param unknown $data        	
	 */
	private function createnotice($data) {
		$tpl = C ( 'MSG_TYPE_CONTENT_PATH' );
		$tpl = $tpl ['ORDER'];
		$nt = C ( 'MSG_TYPE_TITLE_GROUP' );
		$nt = $nt ['ORDER'];
		$m = new view_goods_listModel ();
		$m = $m->getgoodsdetails ( ( int ) $data ['GoodsId'], 2 );
		$cdata ['Title'] = $m ['Title'];
		$cdata ['GURL'] = U ( 'Home/Index/g_show', array (
				'Id' => ( int ) $data ['GoodsId'] 
		) );
		/* 卖家信息 */
		$selldata = $this->CBSN ( $data ['SellerAddId'] );
		/* 买家信息 */
		$buydata = $this->CBSN ( $data ['BuyerAddId'] );
		$m = new noticeModel ();
		/* 卖家通知 */
		$snd = array_merge ( $cdata, $buydata );
		$sc ['Content'] = CNC ( $snd, $tpl ['SELL'] );
		$sc ['Title'] = $nt ['SELL'];
		$sc ['SendId'] = 0;
		$sc ['RecipientId'] = ( int ) $data ['SellerId'];
		/* 买家通知 */
		$bnd = array_merge ( $cdata, $selldata );
		$bc ['Content'] = CNC ( $bnd, $tpl ['BUY'] );
		$bc ['Title'] = $nt ['BUY'];
		$bc ['SendId'] = 0;
		$bc ['RecipientId'] = ( int ) $data ['BuyerId'];
		/* 创建通知 */
		$rst = ($m->addone ( $bc, 2 )) && ($m->addone ( $sc, 2 ));
		
		/* return (() ()); */
	}
	/**
	 * 创建买家卖家通知
	 *
	 * @param unknown $data        	
	 */
	private function CBSN($uaid) {
		$m = M ( 'view_user_info_address' );
		$m = $m->where ( array (
				'AddId' => ( int ) $uaid 
		) )->find ();
		$cdata ['Nick'] = $m ['Nick'];
		$cdata ['UURL'] = U ( 'Usercenter/User/u_show', array (
				'Id' => $m ['Id'] 
		) );
		$cdata ['Tel'] = $m ['Tel'];
		$cdata ['Content'] = $m ['Contacts'] . '&nbsp;' . $m ['Address'];
		return $cdata;
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
			$msg ['msg'] = '商品已下架或不存在！';
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
			$am = new user_addressModel ();
			$address = $am->getbyid ( ( int ) $data ['SellerAddId'], null, 2 );
			if (! $address) {
				$dal->rollback ();
				return array (
						'status' => 0,
						'msg' => '交易失败，找不到卖家地址' 
				);
			}
			$this->createnotice ( $order );
			$dal->commit ();
			return array (
					'status' => 1,
					'msg' => '交易生效，请与卖家联系',
					'address' =>$address
			);
		}
	}
}