<?php

namespace Home\Controller;

use Home\Model\goodsModel;
use Usercenter\Model\user_addressModel;
use Usercenter\Model\userModel;
use Home\Model\goods_orderModel;

/**
 * 订单处理控制器
 *
 * @author NENER
 *        
 */
class OrderController extends BaseController {
	
	/**
	 * 渲染一个商品订单
	 */
	public function index() {
		$Id = I ( 'Id' );
		if (! IS_POST || ! $Id) {
			$this->error ( '页面不存在', U ( 'Home/Index/index' ) );
			die ();
		}
		$m = new goodsModel ();
		
		/* 获得商品模型 */
		$goods = $m->findone ( $Id );
		if (! $goods) {
			$this->error ( '商品不存在或已下架', U ( 'Home/Index/index' ) );
			die ();
		}
		/* 订单code */
		$arrcode = array (
				'code' => date ( 'YmdHis' ) . $goods ['Id'],
				'time' => time () 
		);
		$m = new user_addressModel ();
		$okey = createonekey ( time () . 'Oranger_Order' );
		if (cookie ( '_okey' )) {
			session ( cookie ( '_okey' ), NULL );
		}
		cookie ( '_okey', $okey );
		session ( $okey, C ( 'GOODS_ORDER_SESSION_VALUE' ) );
		/* 获得地址 */
		$alist = $m->getall ( cookie ( '_uid' ) );
		/* 获得可用的交易方式 */
		$waylist = createtradeway ( $goods ['TradeWay'] );
		$this->assign ( 'ordermodel', $arrcode );
		$this->assign ( 'goods', $goods );
		$this->assign ( 'tradewaylist', $waylist );
		$this->assign ( 'useraddress', $alist );
		$this->display ();
	}
	/**
	 * 确认订单
	 */
	public function createorder() {
		if (! IS_POST) {
			$this->error ( '页面不存在', U ( 'Home/Index/index' ) );
			die ();
		}
		$okey = cookie ( '_okey' );
		if (! $okey || ! session ( $okey ) || ! (session ( $okey ) == C ( 'GOODS_ORDER_SESSION_VALUE' ))) {
			$this->error ( '订单已过期', U ( 'Home/Index/index' ) );
			die ();
		} else {
			session ( $okey, null );
			cookie ( '_okey', null );
		}
		$arr = I ( 'post.' );
		$m = new goods_orderModel ();
		$rst = $m->createone ( $arr );

		if ($rst ['status'] == 0) {
			$this->error ( $rst ['msg'], U ( 'Home/Index/index' ) );
			die ();
		} else {
			$this->assign ( 'omodel', $rst );
			$this->display ();

		}
	}
	
	/**
	 * ajax获得余额
	 */
	public function getbalance() {
		if (! IS_POST) {
			$this->error ( '页面不存在' );
			die ();
		}
		$m = new userModel ();
		$b = $m->getbalance ( cookie ( '_uid' ), 2 );
		$this->success ( $b );
	}
}