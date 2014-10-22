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
	public function index() {
		$model = new userModel ();
		if ($model->islogin ()) {
			$this->success ( '登录成功', U ( 'Home/Index/index' ), 1 );
		} else {
			$this->display ( 'Index/login' );
		}
	}
	/**
	 * 登录
	 */
	public function login() {
		if (! IS_POST) {
			$this->error ( "非法访问" );
		}
		$arr = I ( 'post.' );
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
			cookie ( '_key', $rst ['key'], 30 * 24 * 60 * 60 );
			cookie ( '_uid', $rst ['uid'], 30 * 24 * 60 * 60 );
		}
		session ( $rst ['uid'], $rst ['key'] );
		$this->success ( $rst ['msg'] );
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
}
?>