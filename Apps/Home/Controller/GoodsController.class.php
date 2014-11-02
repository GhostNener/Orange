<?php

namespace Home\Controller;

use Home\Model\goodsModel;
use Home\Model\goods_categoryModel;
use Usercenter\Model\user_addressModel;
use Home\Model\goods_serviceModel;
use Home\Model\goods_imgModel;
use Home\Model\view_goods_listModel;
use Usercenter\Model\userModel;
use Usercenter\Model\view_user_info_avatarModel;

/**
 * 前台商品管理
 *
 * @author DongZ
 *        
 */
class GoodsController extends BaseController {
	
	/**
	 * 自动验证
	 */
	public function _initialize() {
		parent::_initialize ();
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
		$this->assign ( 'usermodel', $usermodel );
	}
	
	/**
	 * 个人商品列表
	 */
	public function index($status = 10) {
		$userid = cookie ( '_uid' );
		// 查询条件
		$wherrArr = array (
				'Status' => $status,
				'UserId' => $userid 
		);
		
		$mode = new view_goods_listModel ();
		$arr = $mode->getlist ( $wherrArr );
		$this->assign ( 'list', $arr ['list'] );
		$this->assign ( 'page', $arr ['page'] );
		$this->display ( 'Goods/index' );
	}
	/**
	 * 渲染商品添加页面
	 */
	public function add() {
		$userid = cookie ( '_uid' );
		// 查询分类
		$clist = new goods_categoryModel ();
		$clist = $clist->getall ();
		// 查询地址
		$amodel = new user_addressModel ();
		$alist = $amodel->getall ( $userid );
		$g_smodel = new goods_serviceModel ();
		$slist = $g_smodel->getall ();
		// $this->assign ( 'slist', $slist );
		// 为解决FF浏览器302错误 必须
		$sname = session_name ();
		$sid = session_id ();
		$cid = cookie ( '_uid' );
		$ckey = cookie ( '_key' );
		$this->assign ( 'cid', $cid );
		$this->assign ( 'ckey', $ckey );
		$this->assign ( 'sname', $sname );
		$this->assign ( 'sid', $sid );
		// 以上代码解决FF302
		$this->assign ( 'slist', $slist );
		$this->assign ( 'alist', $alist );
		$this->assign ( 'clist', $clist )->display ( 'modifgoods' );
	}
	/**
	 * 保存
	 */
	public function save() {
		if (! IS_POST) {
			$this->error ( '页面不存在' );
		}
		$postarr = I ( 'post.' );
		$model = new goodsModel ();
		$postarr ['Server'] = implode ( '|', $postarr ['Server'] );
		$rst = $model->savegoods ( $postarr );
		if (( int ) $rst ['status'] == 0) {
			$this->error ( $rst ['msg'] );
		} else {
			$this->success ( 1 );
		}
	}
	/**
	 * 保存图片 添加记录
	 */
	public function saveimg() {
		if (! IS_POST) {
			$this->error ( '页面不存在' );
		}
		$userid = cookie ( '_uid' );
		$postarr = I ( 'post.' );
		$model = new goods_imgModel ();
		$rst = $model->saveimg ( $postarr, $userid );
		if (( int ) $rst ['status'] == 0) {
			$this->error ( $rst ['msg'] );
		} else {
			$this->success ( ( int ) $rst ['goodsid'] );
		}
	}
	/**
	 * 上传商品图片
	 *
	 * @author NENER 修改
	 */
	public function uploadify() {
		if (empty ( $_FILES )) {
			$this->error ( "页面不存在" );
		}
		$model = new goods_imgModel ();
		$rst = $model->uploadimg ();
		if (( int ) $rst ['status'] == 0) {
			echo json_encode ( array (
					0,
					$rst ['msg'] 
			) );
		} else {
			echo json_encode ( array (
					$rst ['imgid'],
					$rst ['msg'] 
			) );
		}
	}
	/**
	 * 删除图片
	 */
	public function delimg() {
		if (! IS_POST) {
			$this->error ( "页面不存在" );
		}
		if (! I ( 'Id' )) {
			// 没有获得要删除的图片
			$this->error ( "没有获得要删除的图片" );
		}
		$model = new goods_imgModel ();
		$rst = $model->delimg ( ( int ) I ( 'Id' ) );
		if (( int ) $rst ['status'] == 0) {
			$this->error ( $rst ['msg'] );
		} else {
			$this->success ( 1 );
		}
	}
	/**
	 * 获得分类
	 */
	public function getcategory() {
		if (! IS_POST) {
			$this->error ( "页面不存在" );
		}
		$str = I ( 'Title' );
		if (! $str) {
			$this->error ( 0 );
		}
		$model = new goods_categoryModel ();
		$rst = $model->getcategory ( $str );
		if (( int ) $rst ['status'] == 1) {
			$this->success ( json_encode ( array (
					2,
					$rst ['msg'] 
			) ) );
		} else {
			$this->error ( json_encode ( array (
					0,
					$rst ['msg'] 
			) ) );
		}
	}
	/**
	 * 刷新地址
	 */
	public function refreshadd() {
		if (! IS_POST) {
			$this->error ( '页面不存在' );
		}
		$userid = cookie ( '_uid' );
		$model = new user_addressModel ();
		$rst = $model->getall ( $userid );
		if (! $rst) {
			$this->error ( json_encode ( 0 ) );
		}
		$this->success ( json_encode ( $rst ) );
	}
	
	/**
	 * 展示商品 详情 及评论
	 */
	public function showgoods($Id) {
		$goods = D ( "goods" );
		$wherrArr = array (
				'g.Id' => $Id 
		);
		$model = $goods->table ( 'goods g,goods_img i' )->where ( array (
				$wherrArr,
				'i.GoodsId=g.Id' 
		) )->field ( 'i.URL as imgURL,g.*' )->select ();
		$this->assign ( 'model', $model );
		// 评论
		$goods_comment = D ( "goods_comment" );
		// 评论查询条件
		$wherrArr = array (
				'c.GoodsId' => $Id,
				'c.Status' => 10 
		);
		// 查询
		$allComment = $goods_comment->table ( 'goods_comment c,user u' )->where ( array (
				$wherrArr,
				'u.Id=c.UserId' 
		) )->field ( 'u.Nick as UserNick,c.*' )->select ();
		// $allComment2 = $goods_comment -> table('goods_comment c,user u') -> where(array($wherrArr,'u.Id=c.AssesseeId')) ->field('u.Nick as assesseeNick') ->select ();
		for($i = 0; $i < count ( $allComment ); $i ++) {
			if ($allComment [$i] ['AssesseeId'] != "") {
				$user = D ( "user" );
				$s = $user->field ( 'Nick' )->find ( $allComment [$i] ['AssesseeId'] );
				$allComment [$i] ['AN'] = $s ['Nick'];
			}
		}
		$this->assign ( 'allComment', $allComment );
		$this->display ();
	}
}