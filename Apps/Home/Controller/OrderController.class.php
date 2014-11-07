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

		/*获得商品模型  */
		$goods = $m->findone ( $Id );
		if (! $goods) {
			$this->error ( '商品不存在或已下架' );
			die ();
		}
		/*订单code  */
		$arrcode=array('code'=>date('YmdHis').$goods['Id'],'time'=>time());
		$m = new user_addressModel ();
		/*获得地址  */
		$alist = $m->getbyid ( cookie ( '_uid' ) );
		/*获得可用的交易方式  */
		$waylist = $this->createtradeway ( $goods ['TradeWay'] );
		$this->assign('ordermodel',$arrcode);
		$this->assign('goods',$goods);
		$this->assign('tradewaylist',$waylist);
		$this->assign('useraddress',$alist);
		$this->display();
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
						'id' => $id,
						'txt' => '线上' ,
						'msg'=>'用“金橘”作为货币交易,确定后“金橘”将被冻结, 直到确认收货后付款给卖家'
				);
				break;
			case 2 :
				$arr [] = array (
						'id' => $id,
						'txt' => '线下' ,
						'msg'=>'线下联系卖家, 按显示的价格进行交易'
				);
				break;
			case 3 :
				$arr [] = array (
						'id' => 1,
						'txt' => '线上',
						'msg'=>'用“金橘”作为货币交易,确定后“金橘”将被冻结, 直到确认收货后付款给卖家'
				);
				$arr [] = array (
						'id' => 2,
						'txt' => '线下' ,
						'msg'=>'线下联系卖家, 按显示的价格进行交易'
				);
				break;
			default :
				$arr = null;
				break;
		}
		return $arr;
	}
}