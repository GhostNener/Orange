<?php

namespace Usercenter\Model;

use Think\Model;

/**
 * 用户地址模型
 *
 * @author NENER
 *        
 */
class user_addressModel extends Model {
	/**
	 * 自动验证
	 *
	 * @var unknown
	 */
	protected $_validate = array (
			array (
					'Tel',
					'checktel',
					'电话号码不合法！',
					self::MUST_VALIDATE,
					'function' 
			),
			array (
					'IsDefault',
					array (
							1,
							2 
					),
					'参数不合法！',
					self::MUST_VALIDATE,
					'in' 
			),
			array (
					'UserId',
					'checknull',
					'超时！请重新登录！',
					self::MUST_VALIDATE,
					'function' 
			),
			array (
					'Address',
					'checknull',
					'地址不能为空！',
					self::MUST_VALIDATE,
					'function' 
			),
			array (
					'Contacts',
					'checknull',
					'联系人不能为空！',
					self::MUST_VALIDATE,
					'function' 
			),
			array (
					'QQ',
					'checkqq',
					'QQ不合法！',
					self::VALUE_VALIDATE,
					'function' 
			) 
	);
	
	/**
	 * 获取地址
	 *
	 * @param int $userid：用户Id        	
	 * @return array ：所有符合的地址列表
	 */
	public function getall($userid) {
		$arr = $this->order ( 'IsDefault DESC' )->where ( array (
				'Status' => 10,
				'UserId' => $userid 
		) )->select ();
		return $arr;
	}
	
	/**
	 * 添加地址
	 *
	 * @author NENER
	 * @param array $data
	 *        	:_uid,Tel,QQ,Address,IsDefault[0,1],modif
	 * @return array status,msg
	 */
	public function saveone($data) {
		$msg = array (
				'status' => 0,
				'msg' => '数据为空' 
		);
		if (! $data) {
			return $msg;
		}
		$modifarr = array (
				'add',
				'update' 
		);
		if (! in_array ( $data ['modif'], $modifarr )) {
			$msg ['msg'] = '数据为空';
			return $msg;
		}
		$datain = array (
				'UserId' => $data ['_uid'],
				'Tel' => $data ['Tel'],
				'QQ' => $data ['QQ'],
				'Address' => $data ['Address'],
				'IsDefault' => $data ['IsDefault'],
				'Status' => 10 
		);
		$address = $this->create ( $data );
		if (! $address) {
			$msg ['msg'] = '添加失败';
			return $msg;
		}
		$dal = M ();
		$dal->startTrans ();
		$rst2 = 1;
		// 首先判断是不是设置的默认地址
		if (( int ) $data ['IsDefault'] == 1) {
			$rst2 = $this->clerdefault ( $datain ['UserId'] );
		}
		if ($data ['modif'] == 'add') {
			$rst1 = $this->add ( $address );
		} else {
			$rst1 = $this->where ( array (
					'Id' => ( int ) $data ['Id'] 
			) )->save ( $address );
		}
		if ($rst1 && $rst2) {
			$dal->commit ();
			$msg ['msg'] = '添加成功';
			$msg ['status'] = 1;
			return $msg;
		} else {
			$dal->rollback ();
			$msg ['status'] = 0;
			$msg ['msg'] = '添加失败';
			return $msg;
		}
	}
	/**
	 * 清除默认地址
	 *
	 * @param int $uid        	
	 */
	public function clerdefault($uid) {
		if ($this->where ( array (
				'UserId' => $uid,
				'IsDefault' => 1 
		) )->count ()) {
			if ($this->where ( array (
					'UserId' => $uid 
			) )->save ( array (
					'IsDefault' => 0 
			) )) {
				return true;
			} else {
				return false;
			}
		} else {
			return true;
		}
	}
	public function adddefefault($uid){
		$user=M('user')->where(array('Id'=>$uid))->find();
		if(!$user){
			return false;
		}		
		$data=array('UserId'=>$uid,'Address'=>'','IsDefault'=>1,'Status'=>10);
		if(checktel($user['Name'])){
			$data['Tel']=$user['Name'];
		}else if(checkmail($user['E-Mail'])){
			$data['Tel']=$user['E-Mail'];
		}
		$rst=$this->add($data);
		if(!$rst){
			return false;
		}
		return true;
	}
}

?>