<?php

namespace Usercenter\Controller;

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

class IndexController extends BaseController {
	public function _initialize() {
		parent::_initialize ();
	}
	
	public function index(){
		$userid = cookie('_uid');
		//查询关注
		$model = new attentionModel();
		$attn = $model -> getAttention($userid);
		$this->assign('attn',$attn['attnumber']);

		$this->getCommon();
		$this->display();
	}
	
	/**
	 * 查询用户信息
	 */
	public function edit(){
		$this->getCommon();
		$userid = cookie('_uid');
		$model = new view_user_info_avatarModel();
		$arr = $model->getinfo();
		$adder = new  user_addressModel();
		$rst = $adder-> getall($userid);
		if ($arr ['status'] == 1) {
			$this->assign ( 'model', $arr['msg'] );
			$this->assign('address',$rst);
			$this->display();
		} else {
			$this->error ( $arr ['msg'] );
		}
	}
	
	/**
	 * 修改用户信息
	 */
	public function updateUser(){
		$arr = I ( 'post.' );
		$arr['_uid'] = cookie('_uid');
		$model = new userModel();
		$rst = $model->updateUser($arr);
		if (( int )$rst['status'] == 1) {
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
		$limit = 4;
		/* 拼接where */
		$wherebuy = array(
				'BuyerId' => $userid,
		 		'Status' => 10 
		);
		$wheresell = array(
				'SellerId' => $userid,
		 		'Status' => 10 
		);
		$model = new view_goods_order_listModel();
		/* 获得最新 */
		$arrBuy = $model->getorder ( $wherebuy, $limit );
		$arrSell = $model->getorder ( $wheresell, $limit );
		/* 模板赋值 */
		$this->assign ( 'buy', $arrBuy ['list']);
		$this->assign ( 'sell', $arrSell ['list']);
		$this->assign ( 'pagebuy', $arrBuy['page'] );
		$this->assign ( 'pagesell', $arrSell['page'] );
		$this->getcommon ();
		$this->display ();
	}
	
	/**
	 * 在售商品
	 */
	public function sell(){
		$userid = cookie('_uid');
		$limit = 1;
		/* 拼接where */
		$whereall = array (
				'UserId' => $userid,
				'Status' => 10
		);
		/* 获得最新 */
		$model = new view_goods_listModel();
		$likelist = $model->getlist ( $whereall, $limit );
		/* 模板赋值 */
		$this->assign ( 'likelist', $likelist ['list']);
		$this->assign ( 'page', $arr['page'] );
		$this->assign ( 'empty', '<h3 class="text-center text-import">暂无商品</h3>' );
		$this->getcommon ();
		$this->display ();
	}
	
	/**
	 * 已关注
	 */
	public function follow(){
		$userid = cookie('_uid');
		/*拼接查询条件*/
		$limit = 6;
		$whereall = array(
				'UserId' => $userid,
				'Status' => 10
		);
		/* 获得关注 */
		$model = new view_user_attention_listModel();
		$arr = $model -> getattention( $whereall , $limit );
		/* 模板赋值 */
		$this->assign('attention',$arr['list']);
		$this->assign ( 'page', $arr['page'] );
		$this->assign ( 'empty', '<h3 class="text-center text-import">暂无商品</h3>' );
		$this->getCommon();
		$this->display();
	}
	
	/**
	 * 心愿单
	 */
	public function like(){
		$userid = cookie('_uid');
		/*拼接查询条件*/
		$limit = 6;
		$whereall = array(
				'UserId' => $userid,
				'Status' => 10
		);
		/* 获得心愿单 */
		$model = new view_favorite_listModel();
		$arr = $model -> getlist($wherearr, $limit );
		$this->assign('likelist',$arr['list']);
		$this->assign ( 'page', $arr['page'] );
		$this->assign ( 'empty', '<h3 class="text-center text-import">暂无商品</h3>' );
		$this->getCommon();
		$this->display();
	}
	
	/**
	 * 获取相同的模板变量并对模板进行赋值
	 */
	private function getCommon() {
		$userid = cookie('_uid');
		$model = new view_user_info_avatarModel();
		$arr = $model->getinfo();
		if ($arr ['status'] == 1) {
			//获取经验 计算等级
			$EXP = $arr['msg']['EXP'];
			$model2 = new user_gradeModel();
			$rst = $model2 ->getgrade($EXP);
			$this->assign ( 'user', $arr['msg'] );
			$this->assign('grade',$rst);
		}
	}
	
	/**
	 * 添加心愿单
	 */
	public function addfavorite(){
		$userid= cookie('_uid');
		$model = new favoriteModel();
		$rst = $model->addfavorite($Id, $userid);
		if ((int)$rst['status']==1){
			$this->success($rst['msg']);
		}else {
			$this->error($rst['msg']);
		}
	}
}
?>