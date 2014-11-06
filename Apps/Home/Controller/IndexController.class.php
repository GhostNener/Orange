<?php

namespace Home\Controller;

use Home\Model\goods_commentModel;
use Think\Controller;
use Home\Model\goods_orderModel;
use Home\Model\view_goods_listModel;
use Home\Model\view_search_listModel;
use Home\Model\view_goods_in_serviceModel;
use Usercenter\Model\userModel;
use Usercenter\Model\view_user_info_avatarModel;
use Home\Model\goods_categoryModel;
use Home\Model\activityModel;

require_once './ORG/phpAnalysis/SearchDic.class.php';
/**
 * 商品首页
 *
 * @author NENER
 *        
 */
class IndexController extends Controller {
	/**
	 * 自动验证
	 */
	public function _initialize() {
		$user = new userModel ();
		$usermodel = null;
		if ($user->islogin ( null, false, false )) {
			$m = new view_user_info_avatarModel ();
			$usermodel = $m->getinfo ();
			if($usermodel['status']==1){
				$usermodel=$usermodel['msg'];
			}else{
				$usermodel=null;
			}
		}
		$this->assign ( 'usermodel', $usermodel );
	}
	/**
	 * 首页
	 */
	public function index() {
		/* 置顶 最新的 猜你喜欢 分类 */
		$model = new view_goods_in_serviceModel ();
		/* 获得置顶 */
		$toplist = $model->getlist ( array (
				'ServiceId' => 2,
				'Status' => 10 
		), 6 );
		$toplist = $toplist ['list'];
		$wherenew = array (
				'Status' => 10 
		);
		/* 拼接where */
		foreach ( $toplist as $k => $v ) {
			$wherenew [] = array (
					'Id' => array (
							'neq',
							$v ['Id'] 
					) 
			);
		}
		$model = new view_goods_listModel ();
		/* 获得最新 */
		$newlist = $model->getlist ( $wherenew, 6 );
		$newlist = $newlist ['list'];
		/* 获得猜你喜欢 */
		$likelist = $model->getrandlist ( 6 );
		$likelist = $likelist ['list'];
		/* 获得分类 */
		$model=new goods_categoryModel();
		$clist=$model->getall();
		$model=new activityModel();
		$activitylist=$model->getlist();
		$this->assign ( 'topimg', $activitylist );
		$this->assign ( 'toplist', $toplist );
		$this->assign ( 'newlist', $newlist );
		$this->assign ( 'likelist', $likelist );
		$this->assign ( 'clist', $clist );
		$this->display ( 'Index/index' );
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
		$arrtemp = $arr;
		$key = implode ( ' ', $arr );
		$keyt = $key . ' ' . implode ( '', $arrtemp );
		$model = new view_search_listModel ();
		$arr = $model->getlist ( $key, 6 );
		if (count ( $arr ['list'] ) <= 0) {
			$arr = $model->getlist ( $keyt, 6 );
		}
		$this->assign ( 'test', $test );
		$this->assign ( 'list', $arr ['list'] );
		$this->assign ( 'page', $arr ['page'] );
		$this->display ( 'Index/searchgoods' );
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
		$this->assign ( 'imgcount',count( $arr ['goodsimg'] ));
		$this->assign( 'empty', '<h3 class="text-center text-import">暂无评论</h3>');
		$this->display ('Index/showgoods');
	}
	
	/**
	 * 添加评论
	 */
	public function addComment() {
		$postarr = I ( 'post.' );
		if(!cookie('_uid')){
			$this->error ( '没有登录' );
			return false;
		}
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