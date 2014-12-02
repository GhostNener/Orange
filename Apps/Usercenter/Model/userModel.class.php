<?php

namespace Usercenter\Model;

use Think\Model;

/**
 * 用户地址模型
 *
 * @author NENER
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
					'请确保密码在6~18位字符',
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
					'PayPwd',
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
	protected function pwd_md5($Password) {
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
		if (! $cost || ( int ) $cost <= 0) {
			return true;
		} else {
			return ($this->where ( array (
					'Id' => $uid 
			) )->setDec ( 'E-Money', $cost ));
		}
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
			$str = C ( 'RAND_NICK_PREFIX' ) . $data ['E-Mail'];
			$str = str_replace ( '@', 'at', $str );
			$str = str_replace ( '.', '_', $str );
			$data ['Nick'] = C ( 'RAND_NICK_PREFIX' ) . $str;

			unset ( $data ['Name'] );
		} else {
			if (! $data ['Nick']) {
				$data ['Nick'] = C ( 'RAND_NICK_PREFIX' ) . $data ['Name'];
			}
		}

		$dal = M ();
		$dal->startTrans ();
		$data ['PayPwd'] = $data ['Password'];
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
				'Nick',
				'PayPwd' 
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
		$msgarr ['_uname'] = base64_encode ( $rst ['Nick'] );
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
		$pwd = strtoupper ( sha1 ( $temp, FALSE ) );
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
				'Id' => ( int ) $userid,
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
			$msgarr ['msg'] = "获取用户信息失败";
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
	 *
	 * @param
	 *        	array
	 * @return array status msg
	 * @author LongG
	 */
	public function updateinfo($data, $uid) {
		$arr = $this->where ( array (
				'Id' => array (
						'neq',
						$uid 
				),
				'Nick' => $data ['Nick'] 
		) )->find ();
		$data ['Nick'] = trim ( $data ['Nick'] );
		if (! $data ['Nick']) {
			$msg ['status'] = 0;
			$msg ['msg'] = '昵称不能为空！';
			return $msg;
		}
		if ($arr) {
			$msg ['status'] = 0;
			$msg ['msg'] = '昵称已存在！';
			return $msg;
		}
		if (! preg_match ( '/^[\x{4e00}-\x{9fa5}A-Za-z0-9_]{1,60}$/ui', $data ['Nick'] )) {
			$msg ['msg'] = '昵称不能包含特殊字符！' . $data ['Nick'];
			return $msg;
		}
		$datain = array (
				'RealName' => trim ( $data ['RealName'] ),
				'Nick' => trim ( $data ['Nick'] ),
				'Sex' => trim ( $data ['Sex'] ),
				'TP_QQ' => trim ( $data ['QQ'] ),
				'Birthday' => $data ['Birthday'] 
		);
		$rst = $this->where ( array (
				'Id' => $uid 
		) )->save ( $datain );
		if ($rst) {
			$msg ['status'] = 1;
			$msg ['msg'] = '修改成功！';
			return $msg;
		} else {
			$datain ['Id'] = $uid;
			if (! $this->where ( $datain )->find ()) {
				$msg ['msg'] = '修改失败！';
			} else {
				$msg ['msg'] = '你没做任何修改！';
			}
			$msg ['status'] = 0;
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
			$this->handleEXP ( $uid, $c, true, true );
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
	
	/**
	 * 检查用户是否存在
	 *
	 * @param string $idornick
	 *        	id或nick
	 * @param number $type
	 *        	1：表示Id，2：nick
	 * @return boolean
	 */
	public function checkuserid($idornick, $type = 1, $isreturnmodel = false) {
		if (! trim ( $idornick )) {
			return false;
		}
		$wherearr = array (
				'Status' => 10 
		);
		if ($type == 2) {
			$wherearr ['Nick'] = $idornick;
		} else {
			$wherearr ['Id'] = $idornick;
		}
		$rst = $this->where ( $wherearr )->find ();
		if ($isreturnmodel) {
			return $rst;
		}
		if (! $rst) {
			// 不存在
			return false;
		} else {
			// 存在
			return true;
		}
	}
	/**
	 * 修改密码
	 *
	 * @param array $data
	 *        	:NewPassword,OldPassword, ConfirmPwd,
	 * @param int $type:1,登录密码，2：支付密码        	
	 * @param string $uid        	
	 * @return multitype:number string
	 *        
	 */
	public function changepwd($data, $type, $uid = NULL) {
		if (! $uid) {
			$uid = cookie ( '_uid' );
		}
		$np = $data ['NewPassword'];
		$data = array (
				'OldPassword' => $data ['OldPassword'],
				'ConfirmPassword' => $data ['ConfirmPwd'] 
		);
		if ($type == 1) {
			$data ['Password'] = $np;
		} elseif ($type == 2) {
			$data ['PayPwd'] = $np;
			$data ['Password'] = $np;
		} else {
			return array (
					'status' => 0,
					'msg' => '操作不存在' 
			);
		}
		$u = $this->where ( array (
				'Id' => $uid,
				'Status' => array (
						'neq',
						- 1 
				) 
		) )->find ();
		if (! $u) {
			return array (
					'status' => 0,
					'msg' => '用户不存在' 
			);
		}
		
		$rs = $this->create ( $data );
		if (! $rs) {
			return array (
					'status' => 0,
					'msg' => $this->getError () 
			);
		}
		if ($type == 2) {
			unset ( $data ['Password'] );
		}
		$npwd = $this->encrypt ( $data ['OldPassword'], $u ['RegistTime'] );
		if ($type == 1) {
			$op = $u ['Password'];
		} else {
			$op = $u ['PayPwd'];
		}
		if ($npwd != $op) {
			return array (
					'status' => 0,
					'msg' => '原密码错误' 
			);
		}
		if ($type == 1) {
			$filed = 'Password';
			$ms = ',请重新登录';
			$kt = 0;
		} else {
			$filed = 'PayPwd';
			$ms = '';
			$kt = $u ['LastKeyTime'];
		}
		$npwd = $this->encrypt ( $data [$filed], $u ['RegistTime'] );
		$rs = $this->where ( array (
				'Id' => $uid,
				'Status' => 10 
		) )->save ( array (
				$filed => $npwd,
				'LastKeyTime' => $kt 
		) );
		if (! $rs) {
			return array (
					'status' => 0,
					'msg' => '修改失败' 
			);
		} else {
			return array (
					'status' => 1,
					'msg' => '修改成功' . $ms 
			);
		}
	}
	/**
	 * 用户经验值处理
	 *
	 * @param string $uid
	 *        	用户id
	 * @param number $type
	 *        	类型：1：留言收藏回复，2：参与活动，3：上架，4：售出，5：购买，6：完成心愿单
	 * @param string $isInc
	 *        	是不是增加，默认是
	 * @param string $isclockin
	 *        	是不是签到,默认不是
	 * @return
	 *
	 *
	 *
	 *
	 */
	public function handleEXP($uid = null, $type = 1, $isInc = true, $isclockin = false) {
		if (! $uid) {
			$uid = cookie ( '_uid' );
		}
		$wa = array (
				'Id' => $uid 
		);
		if ($isclockin) {
			$type = $type <= C ( 'MAX_CLOCKIN_EXP' ) ? $type : C ( 'MAX_CLOCKIN_EXP' );
			return $this->where ( $wa )->setInc ( 'EXP', ( int ) $type );
		}
		$n = 1;
		switch ((int)$type) {
			case 1 : // 留言收藏
				$n = 1;
				break;
			case 2 : // 参与活动
				$n = 4;
				break;
			case 3 : // 上架
				$n = 5;
				break;
			case 4 : // 售出
				$n = 5;
				break;
			case 5 : // 购买
				$n = 10;
				break;
			case 6 : // 给别人完成心愿单
				$n = 10;
				break;
			default :
				$n = 1;
				break;
		}
		if ($isInc) {
			return $this->where ( $wa )->setInc ( 'EXP', $n );
		} else {
			return $this->where ( $wa )->setDec ( 'EXP', $n );
		}
	}
	
	/**
	 * 帐号绑定
	 *
	 * @param array $arr
	 *        	key,mail
	 * @param int $type
	 *        	1:mail绑定
	 * @param int $uid        	
	 * @return array status,msg
	 */
	public function bundling($arr, $type = 1, $uid = null) {
		if (! $uid) {
			$uid = ( int ) cookie ( '_uid' );
		}
		$key = trim ( $arr ['key'] );
		$mail = $arr ['mail'];
		$mail = base64_decode ( $mail );
		if (! $key || ! checkmail ( $mail )) {
			return array (
					'status' => 0,
					'msg' => '数据不完整' 
			);
		}
		$u = $this->where ( array (
				'Id' => $uid,
				'UserKey' => $key 
		) )->find ();
		if (! $u) {
			return array (
					'status' => 0,
					'msg' => '链接已失效' 
			);
		}
		if (($this->where ( array (
				'E-Mail' => $mail 
		) )->count ()) > 0) {
			return array (
					'status' => 0,
					'msg' => '邮箱已' . $mail . '被使用' 
			);
		}
		if ($u ['E-Mail']) {
			return array (
					'status' => 0,
					'msg' => '帐号已经绑定了邮箱' 
			);
		}
		$nkey = $this->getnewkey ( $uid );
		$r = $this->where ( array (
				'Id' => $uid 
		) )->save ( array (
				'LastKeyTime' => time (),
				'E-Mail' => $mail,
				'UserKey' => $nkey 
		) );
		if (! $r) {
			return array (
					'status' => 0,
					'msg' => '链接已失效或数据不完整' 
			);
		} else {
			cookie ( '_key', $nkey );
			return array (
					'status' => 1,
					'msg' => 'ok' 
			);
		}
	}
	/**
	 * 发送帐号绑定邮件
	 *
	 * @param string $mail        	
	 * @param int $uid        	
	 * @return array ststus,msg
	 */
	public function sendbundlingmail($mail, $uid) {
		$mail = trim ( $mail );
		if (! checkmail ( $mail )) {
			return array (
					'status' => 0,
					'msg' => '邮箱' . $mail . '格式不正确' 
			);
		}
		if (($this->where ( array (
				'E-Mail' => $mail 
		) )->count ()) > 0) {
			return array (
					'status' => 0,
					'msg' => '邮箱' . $mail . '已被使用' 
			);
		}
		$u = $this->where ( array (
				'Id' => $uid 
		) )->find ();
		if (! $u) {
			return array (
					'status' => 0,
					'msg' => '用户未登录' 
			);
		}
		$key = createonekey ( $mail, 11, 11 );
		$d = M ();
		$d->startTrans ();
		$r1 = $this->where ( array (
				'Id' => $uid 
		) )->save ( array (
				'UserKey' => $key,
				'LastKeyTime' => time () 
		) );
		if (! $r1) {
			$d->rollback ();
			return array (
					'status' => 0,
					'msg' => '邮件发送失败' 
			);
		}
		$content = file_get_contents ( C ( 'BUND_MAIL_TPL_PATH' ) );
		$content = str_replace ( '[$_USERNAME_$]', $mail, $content );
		$content = str_replace ( '[$_URL_$]', U ( '/u/bundlmail/' . $key . '/' . base64_encode ( $mail ), '', true, true ), $content );
		if (sendEmail ( '帐号绑定', $content, $mail )) {
			$d->commit ();
			cookie ( "_key", $key );
			return array (
					'status' => 1,
					'msg' => 'ok' 
			);
		}
	}
	/**
	 * 发送邮件（密码找回）
	 *
	 * @param unknown $email        	
	 * @return multitype:number string
	 */
	public function sendfindpwdmail($email, $type = 1) {
		$email = trim ( $email );
		if (! $email || ! checkmail ( $email )) {
			return array (
					'status' => 0,
					'msg' => '邮箱不可用' 
			);
		}
		$m = $this->where ( array (
				'E-Mail' => $email,
				'Status' => array (
						'neq',
						- 1 
				) 
		) )->find ();
		if (! $m) {
			return array (
					'status' => 0,
					'msg' => '用户不存在' 
			);
		}
		$key = createonekey ( $email, 17, 16 );
		if (! $this->where ( array (
				'Id' => $m ['Id'] 
		) )->save ( array (
				'UserKey' => $key,
				'LastKeyTime' => time () 
		) )) {
			return array (
					'status' => 0,
					'msg' => '邮件发送失败' 
			);
		}
		$content = file_get_contents ( C ( 'FIND_PWD_MAIL_TPL_PATH' ) );
		$content = str_replace ( '[$_USERNAME_$]', $email, $content );
		if ($type == 2) {
			$title = "支付密码找回";
			$content = str_replace ( '[$_URL_$]', U ( '/u/resetpaypwd/' . $key, null, true, true ), $content );
		} else {
			$title = "登录密码找回";
			$content = str_replace ( '[$_URL_$]', U ( '/u/resetpwd/' . $key, null, true, true ), $content );
		}
		
		if (sendEmail ( $title, $content, $email )) {
			if ($type == 2) {
				cookie ( '_key', $key );
			}
			return array (
					'status' => 1,
					'msg' => '发送成功' 
			);
		}
		return array (
				'status' => 0,
				'msg' => '邮件发送失败' 
		);
	}
	/**
	 * 保存密码(密码找回)
	 *
	 * @param array $arr        	
	 * @param string $key        	
	 * @param string $type
	 *        	:1,登录密码，2，支付密码
	 */
	public function resetpwd($arr, $key, $type = 1) {
		$u = $this->where ( array (
				'UserKey' => $key,
				'Status' => array (
						'neq',
						- 1 
				) 
		) )->find ();
		if (! $u) {
			return array (
					'status' => 0,
					'msg' => '保存失败' 
			);
		}
		$data = array (
				'ConfirmPassword' => $arr ['ConfirmPassword'],
				'Password' => $arr ['Password'] 
		);
		if (! $this->create ( $data )) {
			return array (
					'status' => 0,
					'msg' => $this->getError () 
			);
		}
		$pwd = $this->encrypt ( $data ['Password'], $u ['RegistTime'] );
		$key = $this->getnewkey ( $u ['Id'] );
		if ($type == 2) {
			$filed = 'PayPwd';
		} else {
			$filed = 'Password';
		}
		if ($this->where ( array (
				'Id' => $u ['Id'] 
		) )->save ( array (
				$filed => $pwd,
				'UserKey' => $key,
				'LastKeyTime' => time () 
		) )) {
			cookie ( '_uid', $u ['Id'] );
			cookie ( '_key', $key );
			return array (
					'status' => 1,
					'msg' => '保存成功' 
			);
		}
		return array (
				'status' => 0,
				'msg' => '保存失败' 
		);
	}
	/**
	 * 校验支付密码
	 *
	 * @param unknown $pwd        	
	 * @param string $uid        	
	 * @return array status,msg
	 */
	public function checkpaypwd($pwd, $uid = null, $isadd = false) {
		if (! $uid) {
			$uid = cookie ( '_uid' );
		}
		$u = $this->where ( array (
				'Id' => $uid 
		) )->find ();
		if (! $u) {
			return array (
					'status' => 0,
					'msg' => '登录过期或用户不存在' 
			);
		}
		if (! $u ['PayPwd']) {
			if (! $isadd) {
				return array (
						'status' => 0,
						'msg' => '暂未设置支付密码，请到个人中心设置' 
				);
			} else {
				return array (
						'status' => 1,
						'msg' => '暂未设置支付密码，请到个人中心设置' 
				);
			}
		}
		$key = $this->encrypt ( $pwd, $u ['RegistTime'] );
		if ($key == $u ['PayPwd']) {
			return array (
					'status' => 1,
					'msg' => 'ok' 
			);
		} else {
			return array (
					'status' => 0,
					'msg' => '支付密码错误' 
			);
		}
	}
	
	/**
	 * 用户信誉度的修改
	 * 
	 * @param int $userid, 
	 * @param int $start 信誉度 
	 * @param int $type 
	 *   		1.增加  2.减少  	
	 * @author LongG
	 */
	public function updatecredit($userid, $star = 0,$type = 1) {
		if ((int)$type == 1) {
			return $this -> where( array('Id' => $userid) )->setInc('Credit', $star); 
		} else {
			return $this -> where( array('Id' => $userid) )->setDec('Credit', $star);
		}
	}
	
	/**
	 * 获得综合排名
	 * @param int $uid
	 * @return int  */
	public function getranking($uid){
		$ranking = $this->query ( 'select ranking from(
								select @rownum := @rownum +1 AS ranking,Id from `user`, (SELECT@rownum :=0) r
								where `Status` = 10 ORDER BY Credit desc,TradeCount desc,EXP desc,ClockinCount desc,`E-Money` desc ) M
								WHERE Id = ' . (int)$uid );
		
		$ranking = $ranking [0] ['ranking'];
		return $ranking;
	}
	/**
	 * 充值
	 * @param unknown $uid
	 * @param unknown $count  */
	public function recharge($uid,$count){
		return $this->where(array('Id'=>$uid))->setInc('E-Money',$count);
	}
}
?>