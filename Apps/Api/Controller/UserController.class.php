<?php

namespace Api\Controller;

use Usercenter\Model\userModel;
use Usercenter\Model\user_addressModel;

/**
 * 用户API
 *
 * @author NENER
 *        
 */
class UserController extends BaseController {
	/**
	 * 校验数据
	 * (non-PHPdoc)
	 *
	 * @see \Api\Controller\BaseController::_initialize()
	 */
	public function _initialize() {
		parent::_initialize ();
		$msg = array (
				'status' => 0,
				'msg' => '非法访问' 
		);
		if (! IS_POST) {
			echo json_encode ( $msg );
			exit ();
		}
		$arr = file_get_contents ( "php://input" );
		$arr = json_decode ( $arr, true );
		if (! $arr) {
			$msg ['msg'] = '空数据';
			echo json_encode ( $msg );
			exit ();
		}
	}
	/**
	 * 登录
	 */
	public function login() {
		$arr = file_get_contents ( "php://input" );
		$arr = json_decode ( $arr, true );
		/* Name Password isadmin */
		$arr ['isadmin'] = false;
		$model = new userModel ();
		$rst = $model->login ( $arr );
		if (( int ) $rst ['status'] == 1) {
			session ( $rst ['_uid'], $rst ['_key'] );
		}
		echo json_encode ( $rst );
	}
	/**
	 * 注册
	 */
	public function regist() {
		$arr = file_get_contents ( "php://input" );
		$arr = json_decode ( $arr, true );
		$model = new userModel ();
		$rst = $model->regist ( $arr );
		echo json_encode ( $rst );
	}
	/**
	 * 激活用户【手机号注册用户】
	 */
	public function active() {
		$arr = file_get_contents ( "php://input" );
		$arr = json_decode ( $arr, true );
		$model = M ( 'user' )->where ( array (
				'Id' => $arr ['_uid'],
				'Status' => 10 
		) )->find ();
		if ($model) {
			echo json_encode ( array (
					'status' => 1,
					'msg' => '已激活，无需重复激活' 
			) );
			return;
		}
		$rst = M ( 'user' )->where ( array (
				'Id' => $arr ['_uid'],
				'Status' => 101 
		) )->save ( array (
				'Status' => 10 
		) );
		if (! $rst) {
			echo json_encode ( array (
					'status' => 0,
					'msg' => '激活失败' 
			) );
			return;
		}
		$add=new user_addressModel();
		$add->adddefefault( $arr ['_uid']);
		echo json_encode ( array (
				'status' => 1,
				'msg' => '激活成功' 
		) );
	}
}