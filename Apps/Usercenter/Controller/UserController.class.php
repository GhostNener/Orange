<?php

namespace Usercenter\Controller;

use Think\Controller;
use Usercenter\Model\userModel;

import ( 'ORG.Util.Image' );
/**
 * 用户控制器
 *
 * @author NENER
 *        
 */
class UserController extends Controller {
	/**
	 * 登录首页
	 */
	public function index($isadmin = false) {
		$model = new userModel ();
		if ($model->islogin ( $isadmin )) {
			if ($isadmin) {
				$this->success ( '登录成功', U ( 'Admin/Index/index' ), 1 );
			} else {
				$this->success ( '登录成功', U ( 'Home/Index/index' ), 1 );
			}
		} else {
			if ($isadmin) {
				$this->assign ( 'admin', true );
			}
			$this->display ( 'Index/login' );
		}
	}
	/**
	 * 注册页面
	 */
	public function regist() {
		$this->display ( 'Index/regist' );
	}
	/**
	 * 登录操作
	 */
	public function login() {
		if (! IS_POST) {
			$this->error ( "非法访问" );
		}
		$arr = I ( 'post.' );
		/* 是不是管理员 */
		$isadmin = ( bool ) $arr ['isadmin'];
		$verifycode = $arr ['verifycode'];
		/* 校验验证码 */
		$rst = $this->check_verify ( $verifycode );
		if (! $rst) {
			$this->error ( '验证码不正确或已过期' );
		}
		$model = new userModel ();
		$rst = $model->login ( $arr );
		if (( int ) $rst ['status'] == 0) {
			$this->error ( $rst ['msg'] );
		}
		if (! ( int ) $arr ['isremeber']) {
			cookie ( '_key', $rst ['key'] );
			cookie ( '_uid', $rst ['uid'] );
		} else {
			cookie ( '_key', $rst ['key'], C ( 'COOKIE_REMEMBER_TIME' ) );
			cookie ( '_uid', $rst ['uid'], C ( 'COOKIE_REMEMBER_TIME' ) );
		}
		if ($isadmin) {
			cookie ( 'admin_key', $rst ['key'] );
			cookie ( 'admin_uid', $rst ['uid'] );
		}
		session ( $rst ['uid'], $rst ['key'] );
		$this->success ( $rst ['msg'] );
	}
	
	/**
	 * 注册一个新用户
	 */
	public function signup() {
		if (! IS_POST) {
			$this->error ( '页面不存在' );
		}
		$arr = I ( 'post.' );
		$model = new userModel ();
		$rst = $model->regis ( $arr );
		if (! ( int ) $rst ['status']) {
			$this->error ( $rst ['msg'] );
		}
		// $rstmail=send_activate_mail('714571611@qq.com','你好橘子');
		$this->success ( $rst ['msg'] );
	}
	
	/* */
	public function active() {
		if (! IS_GET) {
			$this->error ( '页面不存在', U ( 'Home/Index/index' ) );
		}
		$arr = I ( 'get.' );
		if (! $arr) {
			$this->error ( '页面不存在', U ( 'Home/Index/index' ) );
		}
	}
	/**
	 * 验证验证码
	 *
	 * @param unknown $code        	
	 * @param string $id        	
	 * @return boolean
	 */
	private function check_verify($code, $id = '') {
		$verify = new \Think\Verify ();
		$verify->reset = true;
		$rst = $verify->check ( $code, $id );
		return $rst;
	}
	/**
	 * 添加地址
	 */
	public function addaddress() {
		$this->assign ( 'modif', 'add' )->display ( 'Index/modifaddress' );
	}
	/**
	 * 保存地址
	 */
	public function saveaddress() {
		$userid = cookie ( '_uid' );
		$arr = I ( 'post.' );
		if (! IS_POST || ! $arr) {
			$this->error ( '页面不存在' );
		}
		$modifarr = array (
				'add',
				'update' 
		);
		if (! in_array ( $arr ['modif'], $modifarr )) {
			$this->error ( '非法操作' );
		}
		$data = array (
				'UserId' => $userid,
				'Tel' => $arr ['Tel'],
				'QQ' => $arr ['QQ'],
				'Address' => $arr ['Address'],
				'IsDefault' => $arr ['IsDefault'],
				'Status' => 10 
		);
		$dal = M ();
		$dal->startTrans ();
		$rst2 = 1;
		// 首先判断是不是设置的默认地址
		if (( int ) $arr ['IsDefault'] == 1) {
			// 先修改其他默认地址为非默认状态
			if (M ( 'user_address' )->where ( array (
					'UserId' => $userid,
					'IsDefault' => 1 
			) )->count ()) {
				$rst2 = M ( 'user_address' )->where ( array (
						'UserId' => $userid 
				) )->save ( array (
						'IsDefault' => 0 
				) );
			}
		}
		// 添加
		if ($arr ['modif'] == 'add') {
			$rst1 = M ( 'user_address' )->data ( $data )->add ();
		} else {
			// 保存
			$rst1 = M ( 'user_address' )->where ( array (
					'Id' => ( int ) $arr ['Id'] 
			) )->save ( $data );
		}
		if ($rst1 && $rst2) {
			$dal->commit ();
			$this->success ( '操作成功' );
		} else {
			$dal->rollback ();
			$this->error ( '操作失败' );
		}
	}
}
?>