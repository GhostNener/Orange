<?php

namespace Home\Controller;

use Home\Model\goodsModel;
use Usercenter\Model\user_addressModel;

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
			$this->error ( '页面不存在' );
			die ();
		}
		$m = new goodsModel ();
		$goods = $m->findone ( $Id );
		if (! $goods) {
			$this->error ( '商品不存在或已下架' );
			die ();
		}
		$m = new user_addressModel ();
		/*获得地址  */
		$alist = $m->getbyid ( cookie ( '_uid' ) );
		/*获得可用的交易方式  */
		$waylist = $this->createtradeway ( $goods ['TradeWay'] );
	}
	/**
	 * 创建可用的交易方式
	 *
	 * @param unknown $id        	
	 * @return multitype:
	 */
	private function createtradeway($id) {
		switch ($id) {
			case 1 :
				$arr [] = array (
						'Id' => $id,
						'msg' => '线上' 
				);
				break;
			case 2 :
				$arr [] = array (
						'Id' => $id,
						'msg' => '线下' 
				);
				break;
			case 3 :
				$arr [] = array (
						'Id' => 2,
						'msg' => '线下' 
				);
				$arr [] = array (
						'Id' => 1,
						'msg' => '线下' 
				);
				break;
			default :
				$arr = null;
				break;
		}
		return $arr;
	}
}