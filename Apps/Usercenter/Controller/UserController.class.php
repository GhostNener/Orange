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
				$this->success ( '登录成功', U ( 'Admin/Index/index' ), 1 );
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
		} else {
			cookie ( '_key', $rst ['_key'], C ( 'COOKIE_REMEMBER_TIME' ) );
			cookie ( '_uid', $rst ['_uid'], C ( 'COOKIE_REMEMBER_TIME' ) );
			cookie ( '_uname', $rst ['_uname'], C ( 'COOKIE_REMEMBER_TIME' ) );
		}
		if ($isadmin) {
			cookie ( 'admin_key', $rst ['_key'] );
			cookie ( 'admin_uid', $rst ['_uid'] );
			cookie ( 'admin_uname', $rst ['_uname'] );
			cookie ( '_key', $rst ['_key'], C ( 'COOKIE_REMEMBER_TIME' ) );
			cookie ( '_uid', $rst ['_uid'], C ( 'COOKIE_REMEMBER_TIME' ) );
			cookie ( '_uname', $rst ['_uname'], C ( 'COOKIE_REMEMBER_TIME' ) );
		}
		/* cookie ( '_lastLTK', createonekey ( microtime ( true ), 20, 10 ) ); */
		session ( $rst ['_uid'], $rst ['_key'],$rst['_uname'] );
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
	 * @param $Id 被关注人Id        	
	 * @return
	 *
	 *
	 *
	 *
	 *
	 * @author LongG
	 */
	public function u_show($Id) {
		/* 验证客户是否存在 */
		$attenid = $Id;
		$user = new userModel ();
		$bool = $user->checkuserid ( $attenid );
		if (! $bool) {
			$this->redirect ( 'home/Index/index' );
		}
		
		$limit = 100;
		/* 拼接where */
		$wherearr = array (
				'UserId' => $attenid,
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
		$arr = $model->getinfo ( $attenid );
		if ($arr ['status'] == 1) {
			// 获取经验 计算等级
			$EXP = $arr ['msg'] ['EXP'];
			$model2 = new user_gradeModel ();
			$rst = $model2->getgrade ( $EXP );
			/* 模版赋值 */
			$this->assign ( 'user', $arr ['msg'] );
			$this->assign ( 'grade', $rst );
		}
		
		/* 验证用户是否已关注 */
		$uid = cookie ( '_uid' );
		/* 拼接where */
		$where = array (
				'UserId' => $uid,
				'AttentionId' => $attenid 
		);
		$atten = new attentionModel ();
		$msg = $atten->checkIsAtten ( $attenid, cookie ( '_uid' ) );
		/* 模板赋值 */
		$this->assign ( 'selllist', $selllist ['list'] );
		$this->assign ( 'selllist', $selllist ['list'] );
		$this->assign ( 'likelist', $favorite ['list'] );
		$this->assign ( 'md', $msg ['status'] );
		$this->assign ( 'empty', '<h3 class="text-center text-import">暂无商品</h3>' );
		
		// 排行
		$model = M ( 'user' );
		$ranking = $model->query ( 'select ranking from(
								select @rownum := @rownum +1 AS ranking,Id from `user`, (SELECT@rownum :=0) r  
								where `Status` = 10 ORDER BY Credit desc,EXP desc,ClockinCount desc,`E-Money` desc ) M 
								WHERE Id = ' . $attenid );
		
		$ranking = $ranking [0] ['ranking'];
		//签到
		$user = $model-> where(array('Id'=>$attenid,'Status'=>10)) ->find();
		$ClockinCount = $user['ClockinCount'];
		//信誉度
		$credit = $user['Credit']/($user['TradeCount']*5)*100;

		$this->assign('ClockinCount',$ClockinCount);
		$this->assign('ranking',$ranking);
		$this->assign('credit',$credit);
		//销量
		$this->assign('tradecount',$user['TradeCount']);
		
		$this->display ();
	}
	public function lostpwd() {

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
	 * 重置密码（密码找回）
	 */
	public function resetpwd() {
		$key = I ( 'key' );
		$key = trim ( $key );
		if (! $key) {
			redirect ( U ( '/' ) );
		}
		$u = M ( 'user' )->where ( array (
				'UserKey' => $key,
				'Status' => array (
						'neq',
						- 1 
				) 
		) )->find ();
		if (! $u) {
			$this->error ( '链接已过期', U ( '/' ) );
			die ();
		}
		if ((time () - $u ['LastKeyTime']) > C ( 'RESET_PWD_MAIL_TIME' )) {
			$this->error ( '链接已过期', U ( '/' ) );
			die ();
		}
		cookie ( '_fkey', $key );
		$this->assign ( 'fmodel', $u );
		$this->display ();
	}
	/**
	 * 保存密码（密码找回）
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
	 * 发送密码找回邮件
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