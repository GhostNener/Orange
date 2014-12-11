<?php

namespace Home\Model;

use Think\Model;
use Usercenter\Model\userModel;
use Usercenter\Model\user_addressModel;
use Home\Model\goodsModel;
use Home\Model\view_goods_listModel;
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
		$cdata ['GURL'] = U ( '/g/' . ( int ) $data ['GoodsId'] );
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
		$cdata ['UURL'] = U ( '/user/' . $m ['Nick'] );
		$cdata ['Tel'] = $m ['Tel'];
		$cdata ['Content'] = $m ['Contacts'] . '&nbsp;' . $m ['Address'];
		return $cdata;
	}
	
	/**
	 * 修改账单状态
	 *
	 * @param $goodsId 账单Id
	 * @param $Type 类型
	 *        	1.发货 2. 收货
	 * @return array
	 * @author LongG
	 */
	public function update($orderId, $Type = 1, $uid = null) {
		if (! $uid) {
			$uid = cookie ( '_uid' );
		}
		$Type = ( int ) $Type;
		switch ($Type) {
			case 1 :
				$wherearr = array (
				'Id' => $orderId,
				'SellerId' => $uid,
				'Status' => 10
				);
				$where = array (
						'Status' => 21
				);
				break;
			case 2 :
				$wherearr = array (
				'Id' => $orderId,
				'BuyerId' => $uid,
				'Status' => 21
				);
				$where = array (
						'Status' => 22
				);
				break;
			default :
				return "";
				break;
		}
		$c = $this->where ( array (
				'Id' => $orderId
		) )->find ();
		$m = M ();
		$m->startTrans ();
		$rst = $this->where ( $wherearr )->save ( $where );
		if ($Type == 2) {
			if ($c) {
				if ($c ['TradeWay'] == 1) {
					$model = new userModel ();
					// 卖家的积分加
					$sellmsg = $model->where ( array (
							'Id' => $c ['SellerId']
					) )->setInc ( 'E-Money', $c ['Price'] );
				} else {
					$sellmsg = true;
				}
				$r3 = M ( 'user' )->where ( array (
						'Id' => $uid
				) )->setInc ( 'TradeCount', 1 );
				$r4 = M ( 'user' )->where ( array (
						'Id' => $c ['SellerId']
				) )->setInc ( 'TradeCount', 1 );
				$us=new userModel();
				$r5=$us->updatecredit($c ['SellerId'],5,1);
				$r6=$us->updatecredit($c ['BuyerId'],5,1);
			}
		} else {
			$sellmsg = true;
			$r3 = true;
			$r4 = true;
			$r5=true;
			$r6=true;
		}
		if ($rst && $sellmsg && $r3 && $r4&&$r5&&$r6) {
			$m->commit ();
			$m=new userModel();
			$goods=new view_goods_listModel();
			$goods=$goods->getgoodsdetails($c['GoodsId'],2);
			if ($Type == 2) {
				$m=$m->finduser($uid,2);
				CSYSN($c['SellerId'], '确认收货','<a href="/user/'.$m['Nick'].'" class="text-orange">@'.$m['Nick'].'</a>&nbsp;已对你出售的&nbsp;<a href="javascript:void(0)" class="text-orange">'.$goods['Title'].'</a>&nbsp;进行确认收货。');
				handleEXP ( $c ['SellerId'], 4 );
				handleEXP ( $c ['BuyerId'], 5 );
			}else {
				CSYSN($c['BuyerId'], '卖家已发货','你购买的&nbsp;<a href="javascript:void(0)" class="text-orange">'.$goods['Title'].'</a>&nbsp;已经发货了。');
			}
			return array (
					'status' => 1,
					'msg' => "操作成功"
			);
		} else {
			$m->rollback ();
			return array (
					'status' => 0,
					'msg' => "操作失败"
			);
		}
	}
	
	/**
	 * 评价 操作
	 *
	 * @param int $oid
	 * @param int $Star
	 * @param int $uid
	 * @return multitype:number string
	 */
	public function savestar($oid, $Star, $uid = null) {
		$msg = $this->where ( array (
				'Id' => $oid,
				'Status'=>22
		) )->find ();
		$Star = ( int ) $Star;
		if (! $uid) {
			$uid = cookie ( '_uid' );
		}
		if (! $msg) {
			return array (
					'status' => 0,
					'msg' => "订单不存在或订单未完成"
			);
		}
		if($Star<=0||$Star>5){
			return array (
					'status' => 0,
					'msg' => "评价失败:请确保评分在1-5之间"
			);
		}
		if ($msg ['BuyerId'] == $uid) {
			$wherearr = array (
					'SellerStar' => $Star,
					'IsBuyEvaluate' => 1
			);
		} else {
			$wherearr = array (
					'BuyerStar' => $Star,
					'IsSellEvaluate' => 1
			);
		}
		$dal = M ();
		$user = new userModel ();
		$dal->startTrans ();
		$rst = $this->where ( array (
				'Id' => $oid
		) )->save ( $wherearr );
		if($Star==5){
			if($rst){
				$dal->commit ();
				return array (
						'status' => 1,
						'msg' => "评价成功"
				);
			}else{
				$dal->rollback ();
				return array (
						'status' => 0,
						'msg' => "评价失败"
				);
			}
		}
		$Star=5-$Star;
		if ($msg ['BuyerId'] == $uid) {
			$c = $user->updatecredit ( ( int ) $msg ['SellerId'], $Star, 2 );
		} else {
			$c = $user->updatecredit ( ( int ) $msg ['BuyerId'], $Star, 2);
		}
		if (! $rst || ! $c) {
			$dal->rollback ();
			return array (
					'status' => 0,
					'msg' => "评价失败"
			);
		} else {
			$dal->commit ();
			return array (
					'status' => 1,
					'msg' => "评价成功"
			);
		}
	}
	/**
	 * 取消交易 操作
	 *
	 * @param int $oid
	 * @param string $uid
	 * @return
	 *
	 *
	 */
	public function cancelorder($oid, $uid = null) {
		if (! $uid) {
			$uid = cookie ( '_uid' );
		}
		$msg = $this->where ( array (
				'Id' => $oid,
				'BuyerId' => $uid,
				'Status' => 10
		) )->find ();
		if (! $msg) {
			return array (
					'status' => 0,
					'msg' => "取消失败：订单不存在或卖家已发货"
			);
		}
		$m = M ();
		$m->startTrans ();
		$model = new userModel ();
		/* 线上交易 还买家E-money */
		if (( int ) $msg ['TradeWay'] == 1) {
			$saveMoney = $model->where ( array (
					'Id' => $msg ['BuyerId']
			) )->setInc ( 'E-Money', $msg ['Price'] );
		} else {
			$saveMoney = 1;
		}
		/* 减少信誉度 1点 */
		$credit = $model->updatecredit ( $uid, 1, 2 );
		/* 删除该账单 */
		$rst = $this->where ( array (
				'Id' => $oid
		) )->delete ();
		/* 将商品 状态还原为在售 */
		$goods = new goodsModel ();
		$g = $goods->del ( $msg ['GoodsId'], $msg ['SellerId'], 2 );
		if ($saveMoney && $credit && $rst && $g ['status']) {
			$model = $model->finduser ( $uid, 2 );
			CSYSN ( $msg ['UserId'], '取消交易', '<a href="/user/' . $model ['Nick'] . '.html" class="text-orange">@' . $model ['Nick'] . '</a>&nbsp;已取消购买你出售&nbsp;<a href="/g/' . $msg ['Id'] . '.html" class="text-orange">' . $msg ['Title'] . '</a>' );
			$m->commit ();
			return array (
					'status' => 1,
					'msg' => "操作成功"
			);
		} else {
			$m->rollback ();
			return array (
					'status' => 0,
					'msg' => "操作失败"
			);
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
	public function createone($data, $uid = null) {
		$msg ['status'] = 0;
		$m = new goodsModel ();
		$model = $m->findone ( $data ['GoodsId'] );
		if (! $model) {
			$msg ['msg'] = '商品已下架或不存在！';
			return $msg;
		}
		if (! $uid || $uid == - 1) {
			$data ['BuyerId'] = cookie ( '_uid' );
		} else {
			$data ['BuyerId'] = $uid;
		}
		if (( int ) $model ['UserId'] == ( int ) $uid) {
			$msg ['msg'] = '你不能购买自己出售的东西';
			return $msg;
		}
		
		$waylist = createtradeway ( $model ['TradeWay'], 2 );
		if (! in_array ( $data ['TradeWay'], $waylist )) {
			$msg ['msg'] = '交易方式不合法';
			return $msg;
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