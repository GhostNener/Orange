<?php

namespace Usercenter\Model;

use Think\Model;

/**
 * 用户地址模型
 *
 * @author NENER
 *        
 */
class userModel extends Model {
	/**
	 * 登录
	 *
	 * @author NENER
	 * @param array $arr
	 *        	:UserName,Password
	 * @return array :status,msg,key,uid
	 */
	public function login($arr) {
		$msgarr = array (
				'status' => 0,
				'msg' => '用户名或密码错误',
				'key' => '',
				'uid' => 0 
		);
		$uid = trim ( $arr ['Name'] );
		$pwd = $arr ['Password'];
		/* 查询用户 */
		$rst = M ( 'user' )->where ( array (
				'Status' => 10,
				'Name' => $uid 
		) )->find ();
		if (! $rst) {
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
	public function islogin() {
		$_key = cookie ( '_key' );
		$_id = cookie ( '_uid' );
		if ($_id && $_key) {
			$rst = M ( 'user' )->where ( array (
					'Id' => ( int ) $_id,
					'UserKey' => $_key,
					'Status' => 10 
			) )->find ();
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
}
?>