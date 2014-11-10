<?php

namespace Usercenter\Controller;

use Home\Model\goodsModel;

use Usercenter\Model\view_favorite_listModel;
use Usercenter\Model\view_user_attention_listModel;
use Usercenter\Model\view_user_arrention_listModel;
use Home\Model\view_goods_listModel;
use Usercenter\Model\favoriteModel;
use Usercenter\Model\view_user_info_avatarModel;
use Usercenter\Model\view_goods_order_listModel;
use Usercenter\Model\user_addressModel;
use Usercenter\Model\attentionModel;
use Usercenter\Model\userModel;
use Home\Model\goods_listModel;
use Usercenter\Model\user_gradeModel;
use Home\Model\user_avatarModel;
use Usercenter\Model\user_homeModel;
use Usercenter\Controller\BaseController;
use Think\Controller;

class IndexController extends LoginController {
	public function _initialize() {
		parent::_initialize ();
	}

	/**
	 * 个人中心首页 查询用户信息
	 */
	public function index(){
		$userid = cookie('_uid');
		/* 拼接查询条件 */
		$whereall = array(
			'UserId' => $userid
		);
		/* 查询关注数 */
		$model = new attentionModel();
		$attn = $model -> getAttention($whereall);
		/* 模块赋值 */
		$this->assign('attn',$attn);
		$this->getCommon();
		$this->display();
	}
	
	/**
	 * 未读信息
	 */
	public function msg(){
		$this->getCommon();
		$this->display();
	}
	
	/**
	 * 查询用户信息
	 */
	public function edit(){
		$userid = cookie('_uid');
		/*查询所有地址*/
		$adder = new  user_addressModel();
		$rst = $adder-> getall($userid);
		/* 模块赋值 */
		$this->assign ( 'model', $arr['msg'] );
		$this->assign('address',$rst);
		$this->getCommon();
		$this->display();
	}
	
	/**
	 * 修改用户信息
	 */
	public function updateUser() {
		$arr = I ( 'post.' );
		$arr ['_uid'] = cookie ( '_uid' );
		$model = new userModel ();
		$rst = $model->updateUser ( $arr );
		if (( int ) $rst ['status'] == 1) {
			$this->success ( $rst ['msg'] );
		} else {
			$this->error ( $rst ['msg'] );
		}
	}
	
	/**
	 * 订单管理
	 */
	public function order(){
		$userid = cookie('_uid');
		$limit = 8;
		/* 拼接where */
		$wherebuy = array (
				'BuyerId' => $userid,
				'Status' => 10 
		);
		$wheresell = array (
				'SellerId' => $userid,
				'Status' => 10 
		);
		$model = new view_goods_order_listModel ();
		/* 获得最新 */
		$arrBuy = $model->getorder ( $wherebuy, $limit );
		$arrSell = $model->getorder ( $wheresell, $limit );
		/* 模板赋值 */
		$this->assign ( 'buy', $arrBuy ['list'] );
		$this->assign ( 'sell', $arrSell ['list'] );
		$this->assign ( 'pagebuy', $arrBuy ['page'] );
		$this->assign ( 'pagesell', $arrSell ['page'] );
		$this->getcommon ();
		$this->display ();
	}
	
	/**
	 * 在售商品
	 */
	public function sell() {
		$userid = cookie ( '_uid' );
		$limit = 6;
		/* 拼接where */
		$whereall = array (
				'UserId' => $userid,
				'Status' => 10
		);
		/* 获得在售商品 */
		$model = new view_goods_listModel ();
		$likelist = $model->getlist ( $whereall, $limit );
		/* 模板赋值 */
		$this->assign ( 'likelist', $likelist['list'] );
		$this->assign ( 'page', $likelist['page'] );
		$this->assign ( 'empty', '<h3 class="text-center text-import">暂无商品</h3>' );
		$this->getcommon ();
		$this->display ();
	}
	
	/**
	 * 已关注
	 */
	public function follow() {
		$userid = cookie ( '_uid' );
		/* 拼接查询条件 */
		$limit = 6;
		$whereall = array (
				'UserId' => $userid,
				'Status' => 10 
		);
		/* 获得关注 */
		$model = new view_user_attention_listModel ();
		$arr = $model->getattention ( $whereall, $limit );
		/* 模板赋值 */
		$this->assign ( 'attention', $arr ['list'] );
		$this->assign ( 'page', $arr ['page'] );
		$this->assign ( 'empty', '<h3 class="text-center text-import">暂无关注</h3>' );
		$this->getCommon ();
		$this->display ();
	}
	
	/**
	 * 取消关注
	 *
	 */
	public function delattention($AttentionId) {
		$userid = cookie('_uid');
		$dal = M();
		//开始事务
		$dal = startTrans();
		$model = new attentionModel();
		$rst = $model->delattention(array(
				'AttentionId' =>$AttentionId,
				'UserId' => $userid
		));
		$model2 = new goodsModel();
		$c = $model2->VCChhandle($gid,2,false);
		if (!$rst||!$c) {
			//失败 回滚
			$dal->rollback();
			$this->error("操作失败！");
		}else {
			//操作成功 提交事务
			$dal->commit();
		}
	}
	
	/**
	 * 心愿单
	 */
	public function like() {
		$userid = cookie ( '_uid' );
		/* 拼接查询条件 */
		$limit = 6;
		$whereall = array (
				'UserId' => $userid,
				'Status' => 10 
		);
		/* 获得心愿单 */
		$model = new view_favorite_listModel();
		$arr = $model -> getlist($whereall, $limit );
		/* 模板赋值 */
		$this->assign('likelist',$arr['list']);
		$this->assign ( 'page', $arr['page'] );
		$this->assign ( 'empty', '<h3 class="text-center text-import">暂无心愿单</h3>' );
		$this->getCommon ();
		$this->display ();
	}
	
	/**
	 * 个人页面
	 */
	public function home(){
		$userid = cookie ( '_uid' );
		$limit = 100;
		/* 拼接where */
		$whereall = array (
				'UserId' => $userid,
				'Status' => 10
		);
		/* 获得在售商品 */
		$model = new view_goods_listModel ();
		$selllist = $model->getlist ( $whereall );
		/* 获得心愿单 */
		$model = new view_favorite_listModel();
		$arr = $model -> getlist($wherearr, $limit );
		/* 模板赋值 */
		$this->assign( 'selllist', $selllist['list'] );
		$this->assign( 'likelist', $arr['list']);
		$this->assign( 'empty' , '<h3 class="text-center text-import">暂无商品</h3>' );
		$this->getCommon();
		$this->display();
	}
	
	/**
	 * 获取相同的模板变量并对模板进行赋值
	 */
	private function getCommon() {
		$userid = cookie('_uid');
		/* 查询用户信息 */
		$model = new view_user_info_avatarModel();
		$arr = $model->getinfo();
		if ($arr ['status'] == 1) {
			//获取经验 计算等级
			$EXP = $arr['msg']['EXP'];
			$model2 = new user_gradeModel();
			$rst = $model2 ->getgrade($EXP);
			/*模版赋值*/
			$this->assign ( 'user', $arr['msg'] );
			$this->assign('grade',$rst);
		}
	}
	
	/**
	 * 添加心愿单
	 */
	public function addlike() {
		if (! IS_POST || ! I ( 'GoodsId' )) {
			$this->error ( '页面不存在' );
			die ();
		}
		$m = new favoriteModel ();
		$arr = $m->addfavorite ( I ( 'GoodsId' ), cookie ( '_uid' ) );
		if ($arr ['status'] == 0) {
			$this->error ( $arr ['msg'] );
		} else {
			$this->success ( '添加成功' );
		}
	}
}
?>