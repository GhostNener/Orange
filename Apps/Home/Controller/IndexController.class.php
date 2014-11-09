<?php

namespace Home\Controller;

use Home\Model\goods_commentModel;
use Think\Controller;
use Home\Model\view_goods_listModel;
use Home\Model\view_search_listModel;
use Home\Model\view_goods_in_serviceModel;
use Usercenter\Model\userModel;
use Usercenter\Model\view_user_info_avatarModel;
use Home\Model\goods_categoryModel;
use Home\Model\activityModel;
use Home\Model\goodsModel;

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
			if ($usermodel ['status'] == 1) {
				$usermodel = $usermodel ['msg'];
			} else {
				$usermodel = null;
			}
		}
		$isclockin = checkclockin ();
		if ($isclockin) {
			$isclockin = 1;
		} else {
			$isclockin = 0;
		}
		$this->assign ( 'isclockin', $isclockin );
		$this->assign ( 'usermodel', $usermodel );
	}
	/**
	 * 首页
	 */
	public function index() {
		/* $gid = cookie ( '_viewgid' ,null); */
		$limit = 6;
		/* 置顶 最新的 猜你喜欢 分类 */
		$model = new view_goods_in_serviceModel ();
		/* 获得置顶 */
		$toplist = $model->getlist ( array (
				'ServiceId' => C ( 'TOP_SERVICE_ID' ),
				'Status' => 10 
		), $limit );
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
		$newlist = $model->getlist ( $wherenew, $limit );
		$newlist = $newlist ['list'];
		/* 获得猜你喜欢 */
		$likelist = $model->getrandlist ( $limit );
		$likelist = $likelist ['list'];
		
		/* 获取活动图片 */
		$model = new activityModel ();
		$activitylist = $model->getlist ( array (
				'Status' => 10,
				'IsTop' => 1 
		) );
		/* 模板赋值 */
		$this->assign ( 'topimg', $activitylist );
		$this->assign ( 'toplist', $toplist );
		$this->assign ( 'newlist', $newlist );
		$this->assign ( 'likelist', $likelist );
		$this->assign ( 'empty', '<h3 class="text-center text-import">暂无商品</h3>' );
		$this->getheomecommon ();
		$this->display ( 'Index/index' );
	}
	
	/**
	 * 搜索商品
	 *
	 * @author NENER
	 *        
	 */
	public function searchgoods() {
		$test = I ( 'wd' );
		if (! $test) {
			redirect ( U ( 'Home/Index/index' ) );
		}
		$model = new view_search_listModel ();
		$arr = $model->getsearchlist ( $test, 12 );
		/* 获得分类 */
		$this->getheomecommon ();
		$this->assign ( 'test', $test );
		$this->assign ( 'goodlist', $arr ['list'] );
		$this->assign ( 'page', $arr ['page'] );
		$this->assign ( 'empty', '<h3 class="text-center text-import">没有符合的商品</h3>' );
		$this->display ( 'Index/commongoods' );
	}
	/**
	 * 获取不同分类的商品
	 */
	public function cggoods() {
		$id = I ( 'Id' );
		$cmodel = D ( 'goods_category' )->where ( array (
				'Status' => 10,
				'Id' => $id 
		) )->find ();
		if (! $id || ! is_numeric ( $id ) || ! $cmodel) {
			$this->error ( '页面不存在' );
			die ();
		}
		$m = new view_goods_listModel ();
		$arr = $m->getlist ( array (
				'Status' => 10,
				'CategoryId' => $id 
		), 12 );
		$this->getheomecommon ();
		$this->assign ( 'goodlist', $arr ['list'] );
		$this->assign ( 'page', $arr ['page'] );
		$this->assign ( 'cmodel', $cmodel );
		$this->assign ( 'empty', '<h3 class="text-center text-import">' . $cmodel ['Title'] . ' 分类暂无商品</h3>' );
		$this->display ( 'Index/commongoods' );
	}
	
	/**
	 * 获取相同的模板变量并对模板进行赋值
	 */
	private function getheomecommon() {
		/* 分类复制 */
		$model = new goods_categoryModel ();
		$clist = $model->getall ();
		$this->assign ( 'clist', $clist );
		/* 获取活动图片 */
		$model = new activityModel ();
		$hotactivitylist = $model->getlist ( array (
				'Status' => 10,
				'IsHot' => 1 
		), 3 );
		$this->assign ( 'hotactivity', $hotactivitylist );
		$this->assign ( 'emptyact', '<hr><h5 class="text-center text-import">暂无活动</h5>' );
	}
	/**
	 * 展示商品 详情 及评论
	 */
	public function showgoods($Id) {
		$model = new view_goods_listModel ();
		$arr = $model->getgoodsdetails ( $Id );
		if (! $arr || ! $arr ['goods']) {
			$this->error ( '商品不存在或已下架' );
			die ();
		}
		$gid = cookie ( '_viewgid' );
		if (! $gid || ( int ) $gid != ( int ) $Id) {
			$m = new goodsModel ();
			$m->VCChhandle ( $Id, 1 );
			$gid = cookie ( '_viewgid', $Id );
		}
		$this->assign ( 'goods', $arr ['goods'] );
		$this->assign ( 'commentlist', $arr ['commentlist'] );
		$this->assign ( 'goodsimg', $arr ['goodsimg'] );
		$this->assign ( 'imgcount', count ( $arr ['goodsimg'] ) );
		$this->assign ( 'empty', '<h3 class="text-center text-import">暂无评论</h3>' );
		$this->display ( 'Index/showgoods' );
	}
	
	/**
	 * 添加评论
	 */
	public function addComment() {
		$postarr = I ( 'post.' );
		if (! isloin ()) {
			$this->error ( '你还没有登录' );
			die ();
		}
		$model = new goods_commentModel ();
		$rst = $model->addComment ( $postarr );
		if (( int ) $rst ['status'] == 0) {
			$this->error ( $rst ['msg'] );
		} else {
			$m = new goodsModel ();
			$m->VCChhandle ( $postarr ['GoodsId'], 3 );
			$this->success ( 1 );
		}
	}
	/**
	 * 用户签到操作
	 *
	 * @author NENER
	 */
	public function clockin() {
		if (! IS_POST) {
			$this->error ( '页面不存在' );
			die ();
		}
		if (! isloin ()) {
			$this->error ( '你还没有登录' );
			die ();
		}
		$m = new userModel ();
		$arr = $m->clockin ();
		if ($arr ['status'] == 0) {
			$this->error ( $arr ['msg'] );
		} else {
			$this->success ( $arr ['msg'] );
		}
	}
}