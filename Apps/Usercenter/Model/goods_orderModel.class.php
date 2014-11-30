<?php

namespace Usercenter\Model;

use Home\Model\goodsModel;
use Think\Model;
use Usercenter\Model\userModel;
use Home\Model\view_goods_listModel;

class goods_orderModel extends Model {
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
			}
		} else {
			$sellmsg = true;
			$r3 = true;
			$r4 = true;
		}
		if ($rst && $sellmsg && $r3 && $r4) {
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
				CSYSN($c['SellerId'], '卖家已发货','你购买的&nbsp;<a href="javascript:void(0)" class="text-orange">'.$goods['Title'].'</a>&nbsp;已经发货。');
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
				'Id' => $oid 
		) )->find ();
		$Star = ( int ) $Star;
		if (! $uid) {
			$uid = cookie ( '_uid' );
		}
		if (! $msg) {
			return array (
					'status' => 0,
					'msg' => "评价失败" 
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
		if ($msg ['BuyerId'] == $uid) {
			$c = $user->updatecredit ( ( int ) $msg ['SellerId'], $Star, 1 );
		} else {
			$c = $user->updatecredit ( ( int ) $msg ['BuyerId'], $Star, 1 );
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
					'msg' => "取消失败" 
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
					'msg' => $g ['status'] 
			);
		} else {
			$m->rollback ();
			return array (
					'status' => 0,
					'msg' => "操作失败" 
			);
		}
	}
}