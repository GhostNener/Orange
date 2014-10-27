<?php

namespace Home\Controller;

use Think\Controller;
use Home\Model\goods_listModel;

/**
 * 商品首页
 * 
 * @author NENER
 *        
 */
class IndexController extends Controller {
	/**
	 * 首页
	 */
	public function index() {
		$model = new goods_listModel ();
		$arr = $model->getlist ( array (
				'Status' => 10 
		), 6 );
		$this->assign ( 'list', $arr ['list'] );
		$this->assign ( 'page', $arr ['page'] );
		$this->display ( 'Index/home' );
	}
}