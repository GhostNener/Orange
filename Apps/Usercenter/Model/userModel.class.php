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
	/**
	 * 自动验证
	 *
	 * @var unknown
	 */
	protected $_validate = array (
			array (
					'E-Mail',
					'email',
					'邮箱格式错误！',
					self::EXISTS_VALIDATE 
			),
			array (
					'Nick',
					'checknull',
					'昵称不能为空！',
					self::EXISTS_VALIDATE,
					'function' 
			),
			array (
					'Nick',
					'',
					'昵称已被使用！',
					self::EXISTS_VALIDATE,
					'unique' 
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
					'checktel',
					'手机号不合法！',
					self::EXISTS_VALIDATE,
					'function' 
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
					'checkpwd',
					'密码格式不正确',
					self::EXISTS_VALIDATE,
					'function' 
			),
			array (
					'ConfirmPassword',
					'Password',
					'确认密码不一致',
					self::EXISTS_VALIDATE,
					'confirm' 
			) 
	);
	
	/**
	 * 用户模型自动完成
	 *
	 * @var unknown
	 */
	protected $_auto = array (
			array (
					'Password',
					'pwd_md5',
					self::MODEL_INSERT,
					'callback' 
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
	 * 检测用户是否激活
	 *
	 * @param string $uid        	
	 * @return boolean
	 */
	public function isactivated($uid = NULL) {
		if (! $uid) {
			$uid = cookie ( '_uid' );
		}
		$rs = $this->where ( array (
				'Id' => $uid,
				'Status' => 101 
		) )->find ();
		if (! $rs) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * 扣除费用
	 *
	 * @param unknown $uid        	
	 * @param unknown $cost        	
	 * @return boolean Ambigous unknown>
	 */
	public function payEM($uid, $cost) {
		if (( int ) $cost > ($this->getbalance ( $uid, 2 ))) {
			return false;
		}
		return ($this->where ( array (
				'Id' => $uid 
		) )->setDec ( 'E-Money', $cost ));
	}
	
	/**
	 * 获得余额
	 *
	 * @param unknown $uid        	
	 * @param $type 1：返回数组
	 *        	：status msg balance，2返回余额
	 */
	public function getbalance($uid, $type = 1) {
		$r = $this->field ( 'E-Money' )->where ( array (
				'Id' => $uid 
		) )->find ();
		if (! $r) {
			if ($type == 2) {
				return 0;
			}
			return array (
					'status' => 0,
					'msg' => '登录已过期或用户不存在',
					'balance' => 0 
			);
		} else {
			if ($type == 2) {
				return ( int ) $r ['E-Money'];
			}
			return array (
					'status' => 1,
					'msg' => 'ok',
					'balance' => $r ['E-Money'] 
			);
		}
	}
	
	/**
	 * 检查帐号是否可用
	 *
	 * @param array $data        	
	 * @return array:status,msg
	 */
	public function checkexits($data) {
		if (! trim ( $data ['Name'] ) && ! trim ( $data ['E-Mail'] )) {
			$msg ['msg'] = '空数据';
			return $msg;
		}
		if (checkmail ( $data ['Name'] )) {
			$wherearr ['E-Mail'] = $data ['Name'];
		} else {
			$wherearr ['Name'] = $data ['Name'];
		}
		$rst = $this->where ( $wherearr )->find ();
		if (! $rst) {
			return array (
					'status' => 1,
					'msg' => '帐号可用' 
			);
		} else {
			return array (
					'status' => 0,
					'msg' => '帐号已注册' 
			);
		}
	}
	/**
	 * 激活用户【手机号注册用户】
	 *
	 * @param unknown $uid
	 *        	:uid
	 * @return boolean
	 */
	private function activephone($uid) {
		$model = $this->where ( array (
				'Id' => $uid,
				'Status' => 10 
		) )->find ();
		if ($model) {
			return true;
		}
		$dal = M ();
		$dal->startTrans ();
		$rst = $this->where ( array (
				'Id' => $uid,
				'Status' => 101 
		) )->save ( array (
				'Status' => 10,
				'LastKeyTime' => 0 
		) );
		if (! $rst) {
			$dal->rollback ();
			return false;
		}
		/*
		 * $add = new user_addressModel (); $add->adddefefault ( $uid ); $avatar = new user_avatarModel (); $rst2 = $avatar->adddefault ( $uid );
		 */
/* 		if (! $rst2) {
			$dal->rollback ();
			return false;
		} else  */{
			$dal->commit ();
			return true;
		}
	}
	
	/**
	 * 注册一个新用户
	 *
	 * @author NENER
	 * @param array $data
	 *        	Name ,Password ,	ConfirmPassword
	 * @return array :status,_uid,msg
	 */
	public function regist($data, $isapi = false) {
		$msg = array (
				'status' => 0,
				'_uid' => 0 
		);
		if (! trim ( $data ['Name'] ) && ! trim ( $data ['E-Mail'] )) {
			$msg ['msg'] = '空数据';
			return $msg;
		}
		if (checkmail ( $data ['Name'] )) {
			$data ['E-Mail'] = $data ['Name'];
			if (! $data ['Nick']) {
				$data ['Nick'] = C ( 'RAND_NICK_PREFIX' ) . $data ['E-Mail'];
			}
			unset ( $data ['Name'] );
		} else {
			if (! $data ['Nick']) {
				$data ['Nick'] = C ( 'RAND_NICK_PREFIX' ) . $data ['Name'];
			}
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
				'E-Mail',
				'Nick' 
		) )->add ( $user );
		if (! $rst) {
			$msg ['msg'] = '注册失败';
			$dal->rollback ();
			return $msg;
		}
		$send = $this->where ( array (
				'Id' => $rst 
		) )->find ();
		if (! $send) {
			$msg ['msg'] = '注册失败';
			$dal->rollback ();
			return $msg;
		}
		if ($send ['E-Mail'] && ! $send ['Name']) {
			if (! $this->sendactive ( $rst )) {
				$msg ['msg'] = '注册失败!无法发送激活邮件！';
				$dal->rollback ();
				return $msg;
			}
		}
		if ($isapi) {
			$ctp = $this->activephone ( $rst );
			if ($ctp) {
				$msg ['msg'] = $rst;
				$msg ['status'] = 1;
				$msg ['_uid'] = $rst;
				$dal->commit ();
			} else {
				$dal->rollback ();
			}
		} else {
			$msg ['msg'] = $rst;
			$msg ['status'] = 1;
			$msg ['_uid'] = $rst;
			$um = $this->where ( array (
					'Id' => $rst,
					'Status' => 101 
			) )->find ();
			$avatar = new user_avatarModel ();
			$rst2 = $avatar->adddefault ( $um ['Id'] );
			$add = new user_addressModel ();
			$add->adddefefault ( $um ['Id'] );
			cookie ( '_uid', $um ['Id'] );
			cookie ( '_key', $um ['UserKey'] );
			$dal->commit ();
		}
		return $msg;
	}
	/**
	 * 发送激活邮件
	 *
	 * @author NENER
	 * @param int $uid
	 *        	_uid
	 * @return boolean
	 */
	public function sendactive($uid) {
		$send = $this->where ( array (
				'Id' => $uid,
				'Status' => 101 
		) )->find ();
		if (! $send) {
			return false;
		}
		if ($send ['E-Mail']) {
			$key = $this->getnewkey ( $send ['E-Mail'] );
			
			$url = U ( 'Usercenter/User/active', array (
					'key' => $key,
					'uid' => time () 
			), true, true );
			$mailcontent = file_get_contents ( C ( 'ACTIVE_MAIL_TPL_PATH' ) );
			$mailcontent = str_replace ( '[$_USERNAME_$]', $send ['E-Mail'], $mailcontent );
			$mailcontent = str_replace ( '[$_URL_$]', $url, $mailcontent );
			if (! $this->where ( array (
					'Id' => $uid 
			) )->save ( array (
					'UserKey' => $key,
					'LastKeyTime' => NOW_TIME 
			) )) {
				return false;
			}
			if (! send_activate_mail ( $send ['E-Mail'], $mailcontent )) {
				return false;
			}
		} else {
			return false;
		}
		cookie ( '_key', $key );
		return true;
	}
	/**
	 * 激活帐号【邮件】
	 *
	 * @author NENER
	 * @param array $arr
	 *        	激活key
	 * @return multitype:number string
	 */
	public function active($arr) {
		$msg = array (
				'status' => 0,
				'msg' => '页面不存在' 
		);
		if (! $arr ['key']) {
			return $msg;
		}
		$isl = isloin ();
		$rst = $this->where ( array (
				'UserKey' => $arr ['key'],
				'Status' => 101 
		) )->find ();
		if (! $rst) {
			return $msg;
		}
		
		$dal = M ();
		$newkey = $this->getnewkey ( $rst ['Id'] );
		if (! $this->where ( array (
				'Id' => $rst ['Id'] 
		) )->save ( array (
				'UserKey' => $newkey,
				'Status' => 10 
		) )) {
			$msg ['msg'] = '激活失败';
			$dal->rollback ();
			return $msg;
		} else {
			$msg ['msg'] = '激活成功';
			$msg ['status'] = 1;
			$dal->commit ();
			if ($isl) {
				cookie ( '_key', $newkey );
			}
			return $msg;
		}
		return $msg;
	}
	
	/**
	 * 登录
	 *
	 * @author NENER
	 * @param array $arr
	 *        	:Name,Password,isadmin
	 * @return array :status,msg,_key,_uid
	 */
	function login($arr) {
		$msgarr = array (
				'status' => 0,
				'msg' => '用户名或密码错误',
				'_key' => '',
				'_uid' => 0 
		);
		$uid = trim ( $arr ['Name'] );
		if (! $uid) {
			$uid = trim ( $arr ['UserName'] );
		}
		$pwd = $arr ['Password'];
		$wherearr = array ();
		if (checkmail ( $uid )) {
			$wherearr ['E-Mail'] = $uid;
		} else {
			$wherearr ['Name'] = $uid;
		}
		/* 查询用户 */
		if ($arr ['isadmin']) {
			$roleid = $this->getadminroleid ();
			if (! $roleid) {
				return $msgarr;
			}
			$wherearr ['RoleId'] = $roleid;
		}
		$rst = $this->where ( $wherearr )->find ();
		if (! $rst) {
			if ($arr ['isadmin']) {
				$msgarr ['msg'] = "用户名或密码错误!\n或没有权限！";
			}
			return $msgarr;
		}
		/*
		 * if (( int ) $rst ['Status'] == 101) { $msgarr ['msg'] = '帐号未激活'; return $msgarr; }
		 */
		if (( int ) $rst ['Status'] < 10) {
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
		if (! ($this->where ( array (
				'Id' => $rst ['Id'] 
		) )->save ( $data ))) {
			$msgarr ['msg'] = '登录失败';
			return $msgarr;
		}
		$msgarr ['msg'] = '登录成功';
		$msgarr ['_key'] = $key;
		$msgarr ['status'] = 1;
		$msgarr ['_uid'] = $rst ['Id'];
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
		return createonekey ( $uid, 20, 11 );
	}
	/**
	 * 检查是否登录
	 *
	 * @param array $arr
	 *        	api 传来的数据
	 * @param bool $isadmin
	 *        	是否是管理员登录
	 * @param string $isapi
	 *        	是否是api调用
	 * @author NENER
	 * @return boolean
	 */
	public function islogin($arr = null, $isadmin = false, $isapi = false) {
		/* 是否是api调用 */
		if (! $isapi) {
			/* 是否是管理员 */
			if ($isadmin) {
				$_key = cookie ( 'admin_key' );
				$_id = cookie ( 'admin_uid' );
			} else {
				$_key = cookie ( '_key' );
				$_id = cookie ( '_uid' );
			}
			$_LTK = cookie ( '_lastLTK' );
		} else {
			$_key = $arr ['_key'];
			$_id = $arr ['_uid'];
			$_LTK = time ();
		}
		$_key = trim ( $_key );
		$_LTK = trim ( $_LTK );
		if (! $_key || ! $_id) {
			return false;
		}
		if ($_id && $_key) {
			$wherearr = array (
					'Id' => ( int ) $_id,
					'UserKey' => $_key,
					'Status' => array (
							array (
									'gt',
									9 
							),
							array (
									'lt',
									102 
							) 
					) 
			);
			if ($isadmin) {
				$roleid = $this->getadminroleid ();
				if (! $roleid) {
					return false;
				}
				$wherearr ['RoleId'] = $roleid;
			}
			$rst = $this->where ( $wherearr )->find ();
			if (! $rst) {
				return false;
			}
			$keteffecttime = ( int ) C ( 'USER_KEY_EFFECTIVE' );
			if ((time () - ( int ) $rst ['LastKeyTime']) > $keteffecttime) {
				return false;
			}
			$s = session ( $_id );
			if ($s && $_LTK) {
				if ($s == $_key) {
					return true;
					die ();
				}
			}
			session ( $_id, $_key );
			/* 最后一次登录时间key */
			cookie ( '_lastLTK', createonekey ( microtime ( true ), 20, 10 ) );
			$this->where ( array (
					'Id' => $_id 
			) )->save ( array (
					'LastLoginTime' => time () 
			) );
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
	
	/**
	 * 查询用户信息
	 */
	public function finduser($userid, $type = 1) {
		$whereArr = array (
				'Id' => (int)$userid,
				'Status' => array (
						'gt',
						9 
				) 
		);
		$rst = $this->where ( $whereArr )->find ();
		if ($rst) {
			$msgarr ['user'] = $rst;
			$msgarr ['status'] = 1;
		} else {
			$msgarr ['msg'] = "查询失败";
			$msgarr ['status'] = 0;
		}
		if ($type == 2) {
			return $rst;
		} else {
			return $msgarr;
		}
	}
	
	/**
	 * 修改用户信息
	 */
	public function updateUser($data) {
		$datain = array (
				'RealName' => $data ['RealName'],
				'Nick' => $data ['Nick'],
				'Sex' => $data ['Sex'],
				'Birthday' => $data ['Birthday'],
				'TP_QQ' => $data ['TP_QQ'],
				'TP_WeiChat' => $data ['TP_WeiChat'],
				'TP_Weibo' => $data ['TP_Weibo'] 
		);
		$rst = $this->where ( array (
				'Id' => $data ['_uid'] 
		) )->save ( $datain );
		if ($rst) {
			$mag ['status'] = 1;
			$msg ['msg'] = '修改成功！';
		} else {
			$mag ['status'] = 0;
			$msg ['msg'] = '修改失败！';
		}
		return $msg;
	}
	
	/**
	 * 用户签到
	 *
	 * @param int $uid        	
	 */
	public function clockin($uid = -1) {
		$msg ['status'] = 0;
		$msg ['msg'] = '用户不存在或未登录';
		if (! $uid || $uid == - 1) {
			$uid = cookie ( '_uid' );
		}
		if (! $uid) {
			return $msg;
		}
		$user = $this->where ( array (
				'Id' => $uid,
				'Status' => array (
						array (
								'gt',
								9 
						),
						array (
								'lt',
								102 
						) 
				) 
		) )->find ();
		if (! $user) {
			return $msg;
		}
		$lasttime = ( int ) $user ['LastClockinTime'];
		if (($this->checkclockin ( $uid ))) {
			$msg ['msg'] = '今天已经签到过了';
			return $msg;
		}
		/* 连续打卡 */
		if ($lasttime >= strtotime ( date ( 'Y-m-d', strtotime ( '-1 day' ) ) )) {
			$msg ['msg'] = $this->handleclockin ( $uid, 1 );
		} else {
			/* 断签 */
			$msg ['msg'] = $this->handleclockin ( $uid, 2 );
		}
		$msg ['status'] = 1;
		return $msg;
	}
	/**
	 * 签到处理
	 *
	 * @param int $uid
	 *        	用户id
	 * @param int $type
	 *        	1：表示续签，2：表示断签
	 * @return string 签到消息
	 */
	private function handleclockin($uid, $type = 1) {
		$msg = '签到失败，请重试';
		$dal = M ();
		$dal->startTrans ();
		$wa = array (
				'Id' => $uid,
				'Status' => array (
						array (
								'gt',
								9 
						),
						array (
								'lt',
								102 
						) 
				) 
		);
		switch ($type) {
			/* 续签操作 */
			case 1 :
				$r1 = $this->where ( $wa )->setInc ( 'ClockinCount' );
				$r2 = $this->where ( $wa )->save ( array (
						'LastClockinTime' => time () 
				) );
				$c = $this->where ( $wa )->field ( 'ClockinCount' )->find ();
				$c = $c ['ClockinCount'];
				break;
			case 2 :
				$r1 = $this->where ( $wa )->save ( array (
						'LastClockinTime' => time (),
						'ClockinCount' => 1 
				) );
				$r2 = $r1;
				$c = 1;
				break;
			default :
				return $msg;
		}
		if (! $r1 || ! $r2) {
			$dal->rollback ();
			return $msg;
		} else {
			$dal->commit ();
			return '签到成功，已连续签到' . $c . '天';
		}
	}
	/**
	 * 检查今天是否签到了
	 *
	 * @param unknown $uid        	
	 * @return boolean true 签过了，false 没有
	 */
	public function checkclockin($uid = -1) {
		if (! $uid || $uid == - 1) {
			$uid = cookie ( '_uid' );
		}
		$user = $this->where ( array (
				'Id' => $uid,
				'Status' => array (
						array (
								'gt',
								9 
						),
						array (
								'lt',
								102 
						) 
				) 
		) )->find ();
		if (! $user) {
			return true;
		}
		$lasttime = ( int ) $user ['LastClockinTime'];
		if ($lasttime >= ( int ) strtotime ( date ( 'Y-m-d' ) )) {
			return true;
		} else {
			return false;
		}
	}
	/**
	 * 获取连续签到天数
	 *
	 * @param unknown $uid        	
	 * @return number
	 */
	public function getclockincount($uid = -1) {
		if (! $uid || $uid == - 1) {
			$uid = cookie ( '_uid' );
		}
		$user = $this->field ( 'ClockinCount' )->where ( array (
				'Id' => $uid,
				'Status' => array (
						array (
								'gt',
								9 
						),
						array (
								'lt',
								102 
						) 
				) 
		) )->find ();
		if (! $user) {
			return 0;
		} else {
			return ( int ) $user ['ClockinCount'];
		}
	}
}
?>