<?php

namespace Home\Controller;

use Home\Model\goods_commentModel;
use Home\Model\view_goods_listModel;
use Home\Model\view_search_listModel;
use Home\Model\view_goods_in_serviceModel;
use Usercenter\Model\userModel;
use Home\Model\activityModel;
use Home\Model\goodsModel;

/**
 * 商品首页
 *
 * @author NENER
 *        
 */
class IndexController extends BaseController {
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
				'Type' => C ( 'TOP_SERVICE_TYPE' ),
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
		$this->display ( 'Index/index' );
	}
	
	/**
	 * 搜索商品
	 *
	 * @author NENER
	 *        
	 */
	public function searchgoods() {
		$title = I ( 'wd' );
		if (! $title) {
			redirect ( U ( 'Home/Index/index' ) );
		}
		$model = new view_search_listModel ();
		$arr = $model->getsearchlist ( $title, 12,'/s?wd='.$title);
		/* 获得分类 */
		$this->assign ( 'test', $title );
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
		), 12,'/c/'.$id,false );
		$this->assign ( 'goodlist', $arr ['list'] );
		$this->assign ( 'page', $arr ['page'] );
		$this->assign ( 'cmodel', $cmodel );
		$this->assign ( 'empty', '<h3 class="text-center text-import">' . $cmodel ['Title'] . ' 分类暂无商品</h3>' );
		$this->display ( 'Index/commongoods' );
	}
	
	/**
	 * 展示商品 详情 及评论
	 */
	public function g_show() {
		$Id=(int)I('Id');
		$model = new view_goods_listModel ();
		$arr = $model->getgoodsdetails ( $Id );
		if (! $arr || ! $arr ['goods']) {
			$this->error ( '商品不存在或已下架' ,U('/'));
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
		$this->display ( 'Index/g_show' );
	}
	
	/**
	 * 添加评论
	 */
	public function addComment() {
		$postarr = I ( 'post.' );
		if (! isloin ()) {
			$this->error ( '请先登录' );
			die ();
		}
		if (! isactivated ()) {
			$this->error ( '请激活账号后再进行此操作' );
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
			$this->error ( '请先登录' );
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