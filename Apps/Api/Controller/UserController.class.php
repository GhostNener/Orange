<?php

namespace Api\Controller;

use Usercenter\Model\userModel;

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
		$dal = M ();
		$model = new userModel ();
		$rst = $model->regist ( $arr, true );
		if ($rst ['status'] == 1) {
			$rst ['msg'] = "注册成功";
		}
		echo json_encode ( $rst );
	}
	/**
	 * 检查用户是否存在
	 */
	public function checkexist() {
		$arr = file_get_contents ( "php://input" );
		$arr = json_decode ( $arr, true );
		$model = new userModel ();
		$rst = $model->checkexits ( $arr );
		echo json_encode ( $rst );
	}
}