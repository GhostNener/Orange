<?php

namespace Usercenter\Controller;

use Usercenter\Model\attentionModel;
use Usercenter\Model\user_gradeModel;
use Usercenter\Model\view_favorite_listModel;
use Home\Model\view_goods_listModel;
use Usercenter\Model\userModel;
use Usercenter\Model\view_user_info_avatarModel;

import ( 'ORG.Util.Image' );
/**
 * 用户控制器
 *
 * @author NENER
 *        
 */
class UserController extends BaseController {
	
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
	public function u_login($isadmin = false) {
		$model = new userModel ();
		if ($model->islogin ( null, $isadmin, false )) {
			
			if ($isadmin) {
				$this->success ( '登录成功', U ( 'juzi/Index/index' ), 1 );
			} else {
				$this->success ( '登录成功', U ( '/' ), 1 );
			}
		} else {
			if ($isadmin) {
				$this->assign ( 'admin', true );
			}
			$this->display ( 'User/index' );
		}
	}
	/**
	 * 注册页面
	 *
	 * @author NENER
	 */
	public function regist() {
		$this->display ( 'User/regist' );
	}
	/**
	 * 退出
	 */
	public function logout() {
		$uid = cookie ( '_uid' );
		if ($uid) {
			session ( $uid, null );
		}
		cookie ( '_uid', null );
		cookie ( '_key', null );
		cookie ( 'admin_key', null );
		cookie ( 'admin_uid', null );
		redirect ( U ( '/' ) );
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
			cookie ( '_uname', $rst ['_uname'] );
			logs ( $rst ['msg'], 2 );
		} else {
			cookie ( '_key', $rst ['_key'], C ( 'COOKIE_REMEMBER_TIME' ) );
			cookie ( '_uid', $rst ['_uid'], C ( 'COOKIE_REMEMBER_TIME' ) );
			cookie ( '_uname', $rst ['_uname'], C ( 'COOKIE_REMEMBER_TIME' ) );
			logs ( $rst ['msg'], 2 );
		}
		if ($isadmin) {
			cookie ( 'admin_key', $rst ['_key'] );
			cookie ( 'admin_uid', $rst ['_uid'] );
			cookie ( 'admin_uname', $rst ['_uname'] );
			cookie ( '_key', $rst ['_key'], C ( 'COOKIE_REMEMBER_TIME' ) );
			cookie ( '_uid', $rst ['_uid'], C ( 'COOKIE_REMEMBER_TIME' ) );
			cookie ( '_uname', $rst ['_uname'], C ( 'COOKIE_REMEMBER_TIME' ) );
			logs ( '管理员' . $rst ['msg'], 2 );
		}
		/* cookie ( '_lastLTK', createonekey ( microtime ( true ), 20, 10 ) ); */
		session ( $rst ['_uid'], $rst ['_key'], $rst ['_uname'] );
		
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
			$this->error ( '页面不存在', U ( '/' ) );
		}
		$arr = I ( 'get.' );
		if (! $arr) {
			$this->error ( '页面不存在', U ( '/' ) );
		}
		$model = new userModel ();
		$rst = $model->active ( $arr );
		if ($rst ['status'] == 1) {
			
			logs ( '激活成功', 2 );
			
			$this->success ( '激活成功', U ( '/' ) );
		} else {
			$this->error ( '链接已失效', U ( '/' ) );
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
	
	/**
	 * 个人页面
	 *
	 * @author LongG
	 */
	public function u_show($Id) {
		/* 验证客户是否存在 */
		$m = new userModel ();
		$user = $m->checkuserid ( $Id, 2, true );
		if (! $user) {
			$this->redirect ( '/' );
		}
		
		$limit = 100;
		/* 拼接where */
		$wherearr = array (
				'UserId' => $user ['Id'],
				'Status' => 10 
		);
		/* 获得在售商品 */
		$model = new view_goods_listModel ();
		$selllist = $model->getlist ( $wherearr );
		
		/* 获得心愿单 */
		$favomodel = new view_favorite_listModel ();
		$favorite = $favomodel->getlist ( $wherearr, $limit );
		/* 查询用户信息 */		
		$model = new view_user_info_avatarModel ();
		$arr = $model->getinfo ( $user ['Id'] );
		if ($arr ['status'] == 1) {
			$this->assign ( 'user', $arr ['msg'] );
		}
		
		/* 验证用户是否已关注 */
		$uid = cookie ( '_uid' );
		/* 拼接where */
		$where = array (
				'UserId' => $uid,
				'AttentionId' => $user ['Id'] 
		);
		$atten = new attentionModel ();
		$msg = $atten->checkIsAtten ( $user ['Id'], cookie ( '_uid' ) );
		/* 模板赋值 */
		$this->assign ( 'selllist', $selllist ['list'] );
		$this->assign ( 'selllist', $selllist ['list'] );
		$this->assign ( 'likelist', $favorite ['list'] );
		$this->assign ( 'md', $msg ['status'] );
		$this->assign ( 'empty', '<h3 class="text-center text-import">暂无商品</h3>' );
		$this->display ();
	}
	public function lostpwd() {
		$this->assign ( 'findurl', U ( 'u/User/findpwdmail' ) );
		$this->assign ( 'header', '找回登录密码' );
		$this->display ();
	}
	/**
	 * 用户激活页面
	 *
	 * @author NENER
	 */
	public function activated() {
		$u = D ( 'user' )->where ( array (
				'Id' => cookie ( '_uid' ),
				'Status' => 101 
		) )->find ();
		if (! $u || ! checkmail ( $u ['E-Mail'] )) {
			redirect ( U ( '/' ) );
		} else {
			$ex = substr ( strrchr ( $u ['E-Mail'], '@' ), 1 );
			$mailurl = 'http://mail.' . $ex;
			$this->assign ( 'activatedurl', $mailurl );
			$this->display ();
		}
	}
	/**
	 * 重置登录密码（密码找回）
	 */
	public function resetpwd() {
		$key = I ( 'key' );
		$key = trim ( $key );
		if (! $key) {
			redirect ( U ( '/' ) );
		}
		$u = M ( 'user' )->where ( array (
				'ModifKey' => $key,
				'Status' => array (
						'neq',
						- 1 
				) 
		) )->find ();
		if (! $u) {
			$this->error ( '链接已失效', U ( '/' ) );
			die ();
		}
		if ((time () - $u ['LastKeyTime']) > C ( 'RESET_PWD_MAIL_TIME' )) {
			$this->error ( '链接已失效', U ( '/' ) );
			die ();
		}
		cookie ( '_fkey', $key );
		$this->assign ( 'fmodel', $u );
		$this->assign ( 'reseturl', U ( 'u/User/u_resetpwd' ) );
		$this->assign ( 'header', '重置登录密码' );
		$this->display ();
	}
	/**
	 * 保存登录密码（密码找回）
	 */
	public function u_resetpwd() {
		$key = cookie ( '_fkey' );
		if (! IS_POST || ! $key) {
			$this->error ( '不要瞎搞', U ( '/' ) );
		}
		$m = new userModel ();
		$r = $m->resetpwd ( I ( 'post.' ), $key );
		if (( int ) $r ['status'] == 0) {
			$this->error ( $r ['msg'] );
		} else {
			cookie ( '_fkey', null );
			$this->success ( $r ['msg'] );
		}
	}
	/**
	 * 发送登录密码找回邮件
	 *
	 * @author NENER
	 */
	public function findpwdmail() {
		$email = I ( 'post.email' );
		if (! IS_POST || ! $email) {
			$this->error ( '页面不存在', U ( '/' ) );
		}
		$u = new userModel ();
		$r = $u->sendfindpwdmail ( $email );
		if (( int ) $r ['status'] == 0) {
			$this->error ( $r ['msg'] );
		} else {
			$this->success ( '发送成功' );
		}
	}
}
?>