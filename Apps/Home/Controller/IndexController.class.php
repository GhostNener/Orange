<?php

namespace Home\Controller;


use Home\Model\goods_commentModel;
use Think\Controller;
use Home\Model\goodsModel;
use Home\Model\goods_categoryModel;
use Usercenter\Model\user_addressModel;
use Home\Model\goods_serviceModel;
use Home\Model\goods_orderModel;
use Home\Model\goods_imgModel;
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
	
	/**
	 * 展示商品 详情 及评论
	 */
	public function showgoods($Id) {
		$model = new goods_listModel();
		$arr = $model -> getgoodsdetails($Id);
		$this->assign ( 'goods', $arr['goods'] );
		$this->assign ( 'commentlist', $arr['commentlist']);
		$this->assign ( 'goodsimg', $arr['goodsimg']);
		$this->display ();
	}
	
	/**
	 *添加评论
	 */
	public function addComment(){
		$postarr = I ( 'post.' );
		$model = new goods_commentModel();
		$rst = $model->addComment ( $postarr );
		if (( int ) $rst ['status'] == 0) {
			$this->error ( $rst ['msg'] );
		} else {
			$this->success ( 1 );
		}
	}
	
	/**
	 * 购买 填写表单
	 */
	public function order($Id) {
		$model = new goods_orderModel();
		$arr = $model -> fillorder($Id);
		$this->assign('goods_fillorder',$arr['goods_fillorder']);
		$this->assign('useraddrlist',$arr['useraddrlist']);
		$this->display ();
	}
	
	/**
	 * 购买成功 生成表单
	 */
	public function order2() {
		$postarr = I('post');
		$model = new goods_orderModel();
		$rst = $model -> order($postarr);
		if ((int) $rst['status'] == 0){
			$this->error ( $rst ['msg'] );
		}else {
			$this->success ( 1 );
		}
	}
}