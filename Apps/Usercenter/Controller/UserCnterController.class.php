<?php
namespace Usercenter\Controller;

use Usercenter\Model\user_cnterModel;
use Usercenter\Controller\BaseController;
use Think\Controller;

class UserCnterController extends BaseController{

	public function index(){
		
		$this->display();
	}
	
	public function updateUser(){
		$userid = cookie('_uid');
		$model = new user_cnterModel();
		$this->display();
	}
	
	public function selectUser(){
		$userid = cookie('_uid');
		$model1 = new user_cnterModel();
		$model = $model1->selectUser($userid);
		$this->assign ( 'model', $model );
		$this->display();
	}
	
	//已经购买
	public function selectBuy(){
		$userid = cookie('_uid');
		$model = new user_cnterModel();
		$buy = $model->selectAllBuy($userid);
		$this->assign ( 'buy', $buy );
		$this->display();
	}
	
	//已出售
	public function selectSell(){
		$userid = cookie('_uid');
		$model = new user_cnterModel();
		$sell = $model->selectAllSell($userid);
		$this->assign ( 'sell', $sell );
		$this->display();
	}
	
	//正在出售
	public function selectGoods(){
		$userid = cookie('_uid');
		$model = new user_cnterModel();
		$goods = $model->selectAllGoods($userid);
		$this->assign ( 'goods', $goods );
		$this->display();
	}
	
//	//我的关注
//	public function SelectGoods(){
//		$userid = cookie('_uid');
//		$model = new user_cnterModel();
//		$goods = $model->allGoods($userid);
//		$this->assign ( 'goods', $goods );
//		$this->display();
//	}
//	
//	//我的心愿单
//	public function SelectGoods(){
//		$userid = cookie('_uid');
//		$model = new user_cnterModel();
//		$goods = $model->allGoods($userid);
//		$this->assign ( 'goods', $goods );
//		$this->display();
//	}
}