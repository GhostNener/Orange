<?php

namespace Usercenter\Controller;

use Think\Controller;
use Usercenter\Model\userModel;
use Usercenter\Model\view_user_info_avatarModel;

import ( 'ORG.Util.Image' );
/**
 * 用户控制器
 *
 * @author NENER
 *        
 */
class UserController extends Controller {
	
	/**
	 * 自动验证
	 */
	public function _initialize() {
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
	 * 登录首页
	 *
	 * @author NENER
	 */
	public function index($isadmin = false) {
		$model = new userModel ();
		if ($model->islogin ( null, $isadmin, false )) {
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
	 *
	 * @author NENER
	 */
	public function regist() {
		$this->display ( 'Index/regist' );
	}
	public function logout() {
		$uid = cookie ( '_uid' );
		if ($uid) {
			session ( $uid, null );
			cookie ( '_uid', null );
			cookie ( '_key', null );
			cookie ( 'admin_key', null );
			cookie ( 'admin_uid', null );
		}
		redirect ( U ( 'Home/Index/index' ) );
	}
	
	/**
	 * 登录操作
	 *
	 * @author NENER
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
			cookie ( '_key', $rst ['_key'] );
			cookie ( '_uid', $rst ['_uid'] );
		} else {
			cookie ( '_key', $rst ['_key'], C ( 'COOKIE_REMEMBER_TIME' ) );
			cookie ( '_uid', $rst ['_uid'], C ( 'COOKIE_REMEMBER_TIME' ) );
		}
		if ($isadmin) {
			cookie ( 'admin_key', $rst ['_key'] );
			cookie ( 'admin_uid', $rst ['_uid'] );
			cookie ( '_key', $rst ['_key'], C ( 'COOKIE_REMEMBER_TIME' ) );
			cookie ( '_uid', $rst ['_uid'], C ( 'COOKIE_REMEMBER_TIME' ) );
		}
		session ( $rst ['_uid'], $rst ['_key'] );
		$this->success ( $rst ['msg'] );
	}
	
	/**
	 * 注册一个新用户
	 *
	 * @author NENER
	 */
	public function signup() {
		if (! IS_POST) {
			$this->error ( '页面不存在' );
		}
		$arr = I ( 'post.' );
		$arr ['Nick'] = null;
		$arr ['Name'] = $arr ['UserName'];
		unset ( $arr ['UserName'] );
		$model = new userModel ();
		$rst = $model->regist ( $arr );
		if (! ( int ) $rst ['status']) {
			$this->error ( $rst ['msg'] );
		}
		$this->success ( $rst ['msg'] );
	}
	
	/**
	 * 激活【邮件】
	 *
	 * @author NENER
	 *        
	 */
	public function active() {
		if (! IS_GET) {
			$this->error ( '页面不存在', U ( 'Home/Index/index' ) );
		}
		$arr = I ( 'get.' );
		if (! $arr) {
			$this->error ( '页面不存在', U ( 'Home/Index/index' ) );
		}
		$model = new userModel ();
		$rst = $model->active ( $arr );
		if ($rst ['status'] == 1) {
			$this->success ( '激活成功', U ( 'Home/Index/index' ) );
		} else {
			$this->error ( '页面不存在', U ( 'Home/Index/index' ) );
		}
	}
	/**
	 * 验证验证码
	 *
	 * @author NENER
	 * @param
	 *        	$code
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