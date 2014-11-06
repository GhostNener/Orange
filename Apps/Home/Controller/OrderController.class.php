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
		$m=new user_addressModel();
		$alist=$m->getbyid(cookie('_uid'));
	}
}