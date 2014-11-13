<?php

namespace Usercenter\Controller;

use Home\Model\noticeModel;
use Home\Model\goodsModel;
use Usercenter\Model\view_favorite_listModel;
use Usercenter\Model\view_user_attention_listModel;
use Home\Model\view_goods_listModel;
use Usercenter\Model\favoriteModel;
use Usercenter\Model\view_user_info_avatarModel;
use Usercenter\Model\view_goods_order_listModel;
use Usercenter\Model\user_addressModel;
use Usercenter\Model\attentionModel;
use Usercenter\Model\userModel;
use Usercenter\Model\user_gradeModel;
use Usercenter\Model\user_avatarModel;

class IndexController extends LoginController {
	
	/**
	 * 用户激活页面
	 */
	public function activated() {
		$u = D ( 'user' )->where ( array (
				'Id' => cookie ( '_uid' ),
				'Status' => 101 
		) )->find ();
		if (! $u || ! checkmail ( $u ['E-Mail'] )) {
			redirect ( U ( 'Home/Index/index' ) );
		} else {
			$ex = substr ( strrchr ( $u ['E-Mail'], '@' ), 1 );
			$mailurl = 'http://mail.' . $ex;
			$this->assign ( 'activatedurl', $mailurl );
			$this->display ();
		}
	}
	/**
	 * 发送激活邮件
	 */
	public function sendactivatemail() {
		if (! IS_POST) {
			$this->error ( '页面不存在', U ( 'Home/Index/index' ) );
		}
		$u = new userModel ();
		$r = $u->sendactive ( cookie ( '_uid' ) );
		if (! $r) {
			$this->error ( '发送失败' );
		} else {
			$this->success ( '发送成功' );
		}
	}
	
	/**
	 * 个人中心首页 查询用户信息
	 */
	public function index() {
		$userid = cookie ( '_uid' );
		/* 拼接查询条件 */
		$whereall = array (
				'UserId' => $userid 
		);
		/* 查询关注数 */
		$model = new attentionModel ();
		$attn = $model->getAttention ( $whereall );
		/* 模块赋值 */
		$this->assign ( 'attn', $attn );
		$this->getCommon ();
		$this->display ();
	}
	
	/**
	 * 未读信息
	 */
	public function msg() {
		$model = new noticeModel ();
		$all = $model->getunread ();
		$this->assign ( 'urnl', $all );
		$this->assign ( 'empty', '' );
		$this->getCommon ();
		$this->display ();
	}
	
	/**
	 * 查询用户信息
	 */
	public function edit() {
		$userid = cookie ( '_uid' );
		/* 查询所有地址 */
		$adder = new user_addressModel ();
		$rst = $adder->getall ( $userid );
		/* 模块赋值 */
		$this->assign ( 'model', $arr ['msg'] );
		$this->assign ( 'address', $rst );
		$this->getCommon ();
		$this->display ();
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
	public function order() {
		$userid = cookie ( '_uid' );
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
		$this->assign ( 'likelist', $likelist ['list'] );
		$this->assign ( 'page', $likelist ['page'] );
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
	 * 关注
	 */
	public function attention($Id) {
		$AttentionId = $Id;
		$userid = cookie ( '_uid' );
		/* 验证被关注的用户是否存在 */
		$userModel = new userModel ();
		$bool = $userModel->checkuserid ( $AttentionId );
		if (! $bool) {
			$this->redirect ( 'home/Index/index', array (), 0, '页面跳转中...' );
		}
		/* 拼接查询条件 */
		$whereall = array (
				'CreateTime' => time (),
				'AttentionId' => $AttentionId,
				'UserId' => $userid 
		);
		/* 添加关注 */
		$model = new attentionModel ();
		$arr = $model->add ( $whereall );
		if ($arr ['status'] == 0) {
			$this->error ( $arr ['msg'] );
		} else {
			$this->redirect ( 'Usercenter/User/home', array (
					'attenid' => $AttentionId 
			) );
		}
	}
	
	/**
	 * 取消关注
	 */
	public function delattention($Id) {
		$AttentionId = $Id;
		$userid = cookie ( '_uid' );
		$whereall = array (
				'AttentionId' => $AttentionId,
				'UserId' => $userid 
		);
		$model = new attentionModel ();
		$arr = $model->del ( $whereall );
		if ($arr ['status'] == 0) {
			$this->error ( $arr ['msg'] );
		} else {
			$this->redirect ( 'Usercenter/User/home', array (
					'attenid' => $AttentionId 
			) );
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
		$model = new view_favorite_listModel ();
		$arr = $model->getlist ( $whereall, $limit );
		/* 模板赋值 */
		$this->assign ( 'likelist', $arr ['list'] );
		$this->assign ( 'page', $arr ['page'] );
		$this->assign ( 'empty', '<h3 class="text-center text-import">暂无心愿单</h3>' );
		$this->getCommon ();
		$this->display ();
	}
	
	/**
	 * 删除心愿单
	 */
	public function dellike($Id) {
		$GoodsId = $Id;
		$userid = cookie ( '_uid' );
		$dal = M ();
		// 开始事务
		$dal->startTrans ();
		$model = new favoriteModel ();
		$rst = $model->del ( array (
				'GoodsId' => $GoodsId,
				'UserId' => $userid 
		) );
		$goods = new goodsModel ();
		$c = $goods->VCChhandle ( $GoodsId, 2, false );
		if (! $rst || ! $c) {
			// 失败 回滚
			$dal->rollback ();
			$this->error ( "操作失败！" );
		} else {
			// 操作成功 提交事务
			$dal->commit ();
			$this->redirect ( 'Index/like', array (), 0, '页面跳转中...' );
		}
	}
	
	/**
	 * 获取相同的模板变量并对模板进行赋值
	 */
	private function getCommon() {
		$userid = cookie ( '_uid' );
		/* 查询用户信息 */
		$model = new view_user_info_avatarModel ();
		$arr = $model->getinfo ( $userid );
		if ($arr ['status'] == 1) {
			// 获取经验 计算等级
			$EXP = $arr ['msg'] ['EXP'];
			$model2 = new user_gradeModel ();
			$rst = $model2->getgrade ( $EXP );
			/* 模版赋值 */
			$this->assign ( 'user', $arr ['msg'] );
			$this->assign ( 'grade', $rst );
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
		$arr = $m->addone ( I ( 'GoodsId' ), cookie ( '_uid' ) );
		if ($arr ['status'] == 0) {
			$this->error ( $arr ['msg'] );
		} else {
			$this->success ( '添加成功' );
		}
	}
	
	/**
	 * 修改密码
	 *
	 * @author NENER
	 */
	public function changepwd() {
		$data = I ( 'post.' );
		if (! IS_POST || ! $data) {
			$this->error ( '页面不存在' );
			return;
		}
		$m = new userModel ();
		$rs = $m->changepwd ( $data );
		if (( int ) $rs ['status'] == 0) {
			$this->error ( $rs ['msg'] );
		} else {
			session ( cookie ( '_uid' ), null );
			cookie ( '_uid', null );
			cookie ( '_key', null );
			$this->success ( $rs ['msg'] );
		}
	}
	/**
	 * 上传头像
	 * @author NENER
	 */
	public function upload() {
		if (! IS_POST) {
			$this->error ( '页面不存在' );
			return;
		}
		$setting = C ( 'UPLOAD_SITEIMG_QINIU' );
		$setting ['savePath'] = 'Avatar/';
		$Upload = new \Think\Upload ( $setting );
		$info = $Upload->upload ( $_FILES );
		$filename = str_replace ( '/', '_', $info ['AURL'] ['savepath'] ) . $info ['AURL'] ['savename'];
		if (! $filename) {
			echo 0;
			die ();
		}
		$m = new user_avatarModel ();
		if (! $m->addone ( $filename )) {
			echo 0;
		} else {
			echo 1;
		}
	}
}
?>