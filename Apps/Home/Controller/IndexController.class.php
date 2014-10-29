<?php

namespace Home\Controller;

use Home\Model\goods_commentModel;
use Think\Controller;
use Home\Model\goods_orderModel;
use Home\Model\view_goods_listModel;
use Home\Model\view_search_listModel;

require_once './ORG/phpAnalysis/SearchDic.class.php';
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
		$model = new view_goods_listModel ();
		$arr = $model->getlist ( array (
				'Status' => 10 
		), 6 );
		$this->assign ( 'list', $arr ['list'] );
		$this->assign ( 'page', $arr ['page'] );
		$this->display ( 'Index/home' );
	}
	/**
	 * 搜索商品
	 * 
	 * @author NENER
	 *        
	 */
	public function searchgoods() {
		$test = I ( 'text' );
		if (! $test) {
			redirect ( U ( 'Home/Index/index' ) );
		}
		$seach = new \SearchDic ();
		$arr = $seach->searchpart ( $test );
		$arrtemp=$arr;
		$key = implode ( ' ', $arr );
		$arrtemp[]=zhCode($test);
		$keyt=implode ( ' ', $arrtemp );
		$model = new view_search_listModel ();
		$arr = $model->getlist ( $key, 6 );
		if(count($arr['list'])<=0){
			$arr = $model->getlist ( $keyt, 6 );
		}
		$this->assign ( 'test', $test );
		$this->assign ( 'list', $arr ['list'] );
		$this->assign ( 'page', $arr ['page'] );
		$this->display ( 'Index/home' );
	}
	/**
	 * 展示商品 详情 及评论
	 */
	public function showgoods($Id) {
		$model = new view_goods_listModel ();
		$arr = $model->getgoodsdetails ( $Id );
		$this->assign ( 'goods', $arr ['goods'] );
		$this->assign ( 'commentlist', $arr ['commentlist'] );
		$this->assign ( 'goodsimg', $arr ['goodsimg'] );
		$this->display ();
	}
	
	/**
	 * 添加评论
	 */
	public function addComment() {
		$postarr = I ( 'post.' );
		$model = new goods_commentModel ();
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
		$model = new goods_orderModel ();
		$arr = $model->fillorder ( $Id );
		$this->assign ( 'goods_fillorder', $arr ['goods_fillorder'] );
		$this->assign ( 'useraddrlist', $arr ['useraddrlist'] );
		$this->display ();
	}
	
	/**
	 * 购买成功 生成表单
	 */
	public function order2() {
		$postarr = I ( 'post' );
		$model = new goods_orderModel ();
		$rst = $model->order ( $postarr );
		if (( int ) $rst ['status'] == 0) {
			$this->error ( $rst ['msg'] );
		} else {
			$this->success ( 1 );
		}
	}
}