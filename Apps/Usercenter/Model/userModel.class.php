<?php

namespace Usercenter\Model;

use Think\Model;

/**
 * 用户地址模型
 *
 * @author NENER
 *        
 */
define ( 'USER_ROLEID', C ( 'USER_ROLEID' ) );
class userModel extends Model {
	public $_time = NOW_TIME;
	/**
	 * 自动验证
	 *
	 * @var unknown
	 */
	protected $_validate = array (
			array (
					'E-Mail',
					'email',
					'邮箱格式错误！' 
			),
			array (
					'E-Mail',
					'',
					'帐号已注册！',
					self::EXISTS_VALIDATE,
					'unique' 
			),
			array (
					'Name',
					'',
					'帐号已注册！',
					self::EXISTS_VALIDATE,
					'unique' 
			),
			array (
					'Name',
					'chechname',
					'手机号不合法！',
					self::EXISTS_VALIDATE,
					'callback' 
			),
			array (
					'Password',
					'6,18',
					'密码长度错误',
					self::EXISTS_VALIDATE,
					'length' 
			),
			array (
					'Password',
					'checkPwd',
					'密码格式不正确',
					self::EXISTS_VALIDATE,
					'callback' 
			),
			array (
					'ConfirmPassword',
					'Password',
					'确认密码不一致',
					self::EXISTS_VALIDATE,
					'confirm' 
			) 
	);
	
	/* 用户模型自动完成 */
	protected $_auto = array (
			array (
					'Password',
					'pwd_md5',
					self::MODEL_INSERT,
					'callback',
					1 
			),
			array (
					'LastKeyTime',
					NOW_TIME,
					self::MODEL_INSERT 
			),
			
			array (
					'RegistTime',
					NOW_TIME,
					self::MODEL_INSERT 
			),
			array (
					'LastLoginTime',
					NOW_TIME,
					self::MODEL_INSERT 
			),
			array (
					'Status',
					101,
					self::MODEL_INSERT 
			),
			array (
					'RoleId',
					USER_ROLEID,
					self::MODEL_INSERT 
			) 
	);
	/**
	 * 自动完成密码加密
	 *
	 * @param unknown $Password        	
	 * @return string
	 */
	protected function pwd_md5($Password, $RegistTime) {
		return $this->encrypt ( $Password, NOW_TIME );
	}
	/**
	 * 验证密码是否合法
	 *
	 * @param unknown $Password        	
	 * @return boolean
	 */
	protected function checkPwd($Password) {
		$ispwd = "/^(?!\D+$)(?!\d+$)[a-zA-Z0-9_]\w{6,18}$/";
		preg_match ( $ispwd, $Password, $result );
		if (! $result) {
			return false;
		}
		return true;
	}
	/**
	 * 检查手机号
	 * 
	 * @param unknown $Name        	
	 * @return boolean
	 */
	protected function chechname($Name) {
		$isMobile = "/^(?:13\d|14\d|15\d|18\d)\d{5}(\d{3}|\*{3})$/";
		preg_match ( $isMobile, $Name, $result );
		if (! $result) {
			return false;
		}
		return true;
	}
	
	/**
	 * 检查邮件
	 * 
	 * @param unknown $Name        	
	 * @return boolean
	 */
	protected function checmail($mial) {
		$ismail1 = "/^([a-z0-9_\.-]+)@([\da-z\.-]+)\.([a-z\.]{2,6})$/";
		$ismail2 = "/^[a-z\d]+(\.[a-z\d]+)*@([\da-z](-[\da-z])?)+(\.{1,2}[a-z]+)+$/";
		preg_match ( $ismail1, $mial, $result1 );
		preg_match ( $ismail1, $mial, $result2 );
		if (! $result1 && ! $result1) {
			return false;
		}
		return true;
	}
	/**
	 * 注册一个新用户
	 *
	 * @param unknown $data        	
	 * @param number $type        	
	 * @return multitype:number string |multitype:number string multitype:unknown |multitype:number string multitype:unknown Ambigous <\Think\mixed, boolean, string, unknown>
	 */
	public function regis($data) {
		$msg = array (
				'status' => 0 
		);
		if (! trim ( $data ['Name'] ) && ! trim ( $data ['E-Mail'] )) {
			$msg ['msg'] = '空数据';
			return $msg;
		}
		$dal = M ();
		$dal->startTrans ();
		$user = $this->create ( $data );
		if (! $user) {
			$msg ['msg'] = $this->getError ();
			return $msg;
		}
		$rst = $this->field ( array (
				'Name',
				'Password',
				'RegistTime',
				'LastLoginTime',
				'RoleId',
				'Status',
				'E-Mail' 
		) )->add ( $user );
		if (! $rst) {
			$msg ['msg'] = '注册失败';
			$dal->rollback ();
			return $msg;
		}
		
		$send = M ( 'user' )->where ( array (
				'Id' => $rst 
		) )->find ();
		if (! $send) {
			$msg ['msg'] = '注册失败';
			$dal->rollback ();
			return $msg;
		}
		if ($send ['E-Mail'] && ! $send ['Name']) {
			if (! $this->senactive ( $rst )) {
				$msg ['msg'] = '注册失败';
				$dal->rollback ();
				return $msg;
			}
		} else {
			$dal->commit ();
		}
		$dal->commit ();
		$msg ['msg'] = $rst;
		$msg ['status'] = 1;
		return $msg;
	}
	/**
	 * 发送激活邮件
	 *
	 * @param unknown $uid        	
	 * @return boolean
	 */
	public function senactive($uid) {
		$send = M ( 'user' )->where ( array (
				'Id' => $uid,
				'Status' => 101 
		) )->find ();
		if (! $send) {
			return false;
		}
		if ($send ['E-Mail'] && ! $send ['Name']) {
			$key = $this->getnewkey ( $send ['E-Mail'] );
			$url = U ( 'Usercenter/User/active', array (
					'key' => $key,
					'uid' => strtoupper ( md5 ( $uid, true ) ) 
			), true, true );
			$url = '<a href=' . $url . '>点击激活帐号</a>';
			if (! M ( 'user' )->where ( array (
					'Id' => $uid 
			) )->save ( array (
					'UserKey' => $key,
					'LastKeyTime' => NOW_TIME 
			) )) {
				return false;
			}
			if (! send_activate_mail ( $send ['E-Mail'], $url )) {
				return false;
			}
		} else {
			return false;
		}
		return true;
	}
	/**
	 * 激活帐号
	 *
	 * @param unknown $arr        	
	 * @return multitype:number string
	 */
	public function active($arr) {
		$msg = array (
				'status' => 0,
				'msg' => '网页不存在' 
		);
		if (! $arr ['key'] || ! $arr ['uid'] || (strlen ( $arr ['uid'] ) < 32)) {
			return $msg;
		}
		$rst = M ( 'user' )->where ( array (
				'UserKey' => $arr ['key'],
				'Status' => 101 
		) )->find ();
		if (! $rst) {
			return $msg;
		}
		if (strtoupper ( md5 ( $rst ['Id'] ) ) != $arr ['uid']) {
			return $msg;
		}
		$newkey = $this->getnewkey ( $rst ['Id'] );
		if (! M ( 'user' )->where ( array (
				'Id' => $rst ['Id'] 
		) )->save ( array (
				'UserKey' => $newkey,
				'Status' => 10 
		) )) {
			$msg ['msg'] = '激活失败';
			return $msg;
		}
		$msg ['msg'] = '激活成功';
		$msg ['status'] = 1;
		return $msg;
	}
	
	/**
	 * 登录
	 *
	 * @author NENER
	 * @param array $arr
	 *        	:UserName,Password
	 * @return array :status,msg,key,uid
	 */
	function login($arr) {
		$msgarr = array (
				'status' => 0,
				'msg' => '用户名或密码错误',
				'key' => '',
				'uid' => 0 
		);
		$uid = trim ( $arr ['Name'] );
		$pwd = $arr ['Password'];
		$wherearr = array ();
		if ($this->checmail ( $uid )) {
			$wherearr ['E-Mail'] = $uid;
		} else {
			$wherearr ['E-Name'] = $uid;
		}
		/* 查询用户 */
		if ($arr ['isadmin']) {
			$roleid = $this->getadminroleid ();
			if (! $roleid) {
				return $msgarr;
			}
			$wherearr ['RoleId'] = $roleid;
		}
		$rst = M ( 'user' )->where ( $wherearr )->find ();
		if (! $rst) {
			return $msgarr;
		}
		if (( int ) $rst ['Status'] == 101) {
			$msgarr ['msg'] = '帐号未激活';
			return $msgarr;
		}
		if (( int ) $rst ['Status'] != 10) {
			$msgarr ['msg'] = '帐号禁用';
			return $msgarr;
		}
		/* 加密密码 */
		$pwd = $this->encrypt ( $pwd, $rst ['RegistTime'] );
		/* 密码验证 */
		if (! ($pwd == $rst ['Password'])) {
			
			return $msgarr;
		}
		/* 获取用户KEY */
		$key = $rst ['UserKey'];
		/* Key过期时间 */
		$keteffecttime = ( int ) C ( 'USER_KEY_EFFECTIVE' );
		$data = array (
				'LastLoginTime' => time () 
		);
		/* 判断key是否到期，到期之后重新生成 */
		if ((time () - ( int ) $rst ['LastKeyTime']) > $keteffecttime) {
			/* 生成key */
			$key = $this->getnewkey ( $uid );
			$data ['LastKeyTime'] = time ();
		}
		/* 保存登录时间 */
		$data ['UserKey'] = $key;
		if (! (M ( 'user' )->where ( array (
				'Id' => $rst ['Id'] 
		) )->save ( $data ))) {
			$msgarr ['msg'] = '登录失败';
			return $msgarr;
		}
		$msgarr ['msg'] = '登录成功';
		$msgarr ['key'] = $key;
		$msgarr ['status'] = 1;
		$msgarr ['uid'] = $rst ['Id'];
		return $msgarr;
	}
	/**
	 * 用密码盐加密密码
	 *
	 * @author NENER
	 * @param string $source：源密码        	
	 * @param number $RegistTime：时间戳        	
	 * @return string ：结果
	 *        
	 */
	public function encrypt($source = '123', $RegistTime = 0) {
		// $RegistTime=$this->_time;
		/* 密码盐 */
		$salt = C ( 'PDW_SALT' );
		$temp = ( string ) $RegistTime . $source . $salt;
		$pwd = strtoupper ( md5 ( $temp, FALSE ) );
		return $pwd;
	}
	/**
	 * 生成一个唯一Key
	 *
	 * @author NENER
	 * @param string $uid:相对唯一的字符，用户名        	
	 * @return string ：一个新的key
	 *        
	 */
	public function getnewkey($uid) {
		/* 生成key */
		$guid = uniqid ();
		$flag = randstr ( 11 );
		$key = md5 ( $flag . $uid . $guid . microtime ( true ) );
		$key = $key . $flag;
		return $key;
	}
	/**
	 * 检查是否登录
	 *
	 * @author NENER
	 * @return boolean
	 */
	public function islogin($isadmin = false) {
		if ($isadmin) {
			$_key = cookie ( 'admin_key' );
			$_id = cookie ( 'admin_uid' );
		} else {
			$_key = cookie ( '_key' );
			$_id = cookie ( '_uid' );
		}
		if ($_id && $_key) {
			$wherearr = array (
					'Id' => ( int ) $_id,
					'UserKey' => $_key,
					'Status' => 10 
			);
			if ($isadmin) {
				$roleid = $this->getadminroleid ();
				if (! $roleid) {
					return false;
				}
				$wherearr ['RoleId'] = $roleid;
			}
			$rst = M ( 'user' )->where ( $wherearr )->find ();
			if (! $rst) {
				return false;
			}
			$keteffecttime = ( int ) C ( 'USER_KEY_EFFECTIVE' );
			if ((time () - ( int ) $rst ['LastKeyTime']) > $keteffecttime) {
				return false;
			}
			if (! session ( '?' . $_id )) {
				session ( $_id, $_key );
			}
			return true;
		}
	}
	/**
	 * 获得超级管理员的RoleId
	 *
	 * @return boolean Int
	 */
	public function getadminroleid() {
		$adminroleId = M ( 'role' )->where ( array (
				'Name' => C ( 'ADMIN_ROLE_NAME' ),
				'Status' => 10 
		) )->find ();
		if (! adminroleId) {
			return false;
		}
		return $adminroleId ['Id'];
	}
}
?>