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
							0,
							1 
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
	 * 自动完成
	 *
	 * @var unknown
	 */
	protected $_auto = array (
			array (
					'CreateTime',
					NOW_TIME,
					self::MODEL_INSERT 
			) 
	);
	/**
	 * 获取用户所有地址
	 *
	 * @param int $userid：用户Id        	
	 * @return array ：所有符合的地址列表
	 */
	public function getall($userid) {
		$arr = $this->order ( array (
				'IsDefault' => 'DESC',
				'CreateTime' => 'DESC' 
		) )->where ( array (
				'Status' => 10,
				'UserId' => $userid 
		) )->select ();
		return $arr;
	}
	
	/**
	 * 通过地址Id获取单个地址
	 *
	 * @param int $id：地址Id        	
	 * @return array ：符合的地址
	 */
	public function getbyid($id, $uid = null, $type = 1) {
		if (! $uid) {
			$uid = cookie ( '_uid' );
		}
		$arr = array (
				'Id' => $id 
		);
		if ($type == 1) {
			$arr['Status']=10;
			 $arr ['UserId'] =$uid;
		}
		$rst = $this->where ($arr)->order ( 'IsDefault DESC' )->find ();
		return $rst;
	}
	/**
	 * 删除地址
	 */
	public function delbyid($id, $uid = null) {
		if (! $uid) {
			$uid = cookie ( '_uid' );
		}
		$wa = array (
				'Id' => $id,
				'UserId' => $uid,
				'Status' => 10 
		);
		if (! $this->where ( $wa )->find ()) {
			return array (
					'status' => 0,
					'msg' => '地址不存在' 
			);
		}
		$rst = $this->where ( $wa )->save ( array (
				'Status' => 10 
		) ); // $this->where ( $wa )->delete();
		if (! $rst) {
			return array (
					'status' => 0,
					'msg' => '删除失败' 
			);
		}
		return array (
				'status' => 1,
				'msg' => '删除成功' 
		);
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
				'UserId' => $data ['UserId'],
				'Tel' => $data ['Tel'],
				'QQ' => $data ['QQ'],
				'Address' => $data ['Address'],
				'IsDefault' => ( int ) $data ['IsDefault'],
				'Status' => 10,
				'Contacts' => $data ['Contacts'] 
		);
		$address = $this->create ( $datain );
		if (! $address) {
			$msg ['msg'] = $this->getError ();
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
	/**
	 * 添加一个默认地址
	 *
	 * @param unknown $uid        	
	 * @return boolean
	 */
	public function adddefefault($uid) {
		/*
		 * $user=M('user')->where(array('Id'=>$uid))->find(); if(!$user){ return false; } $data=array('UserId'=>$uid,'Contacts'=>$user['Nick'],'IsDefault'=>1,'Status'=>10); $rst=$this->add($data); if(!$rst){ return false; }
		 */
		return true;
	}
	
	/**
	 * 删除地址
	 *
	 * @param int $id        	
	 */
	public function del($id) {
		$whereArr = array (
				'Id' => $id 
		);
		$rst = $this->where ( $whereArr )->setField ( 'Status', - 1 );
		if ($rst) {
			return array (
					'status' => 1,
					'msg' => "删除成功" 
			);
		} else {
			return array (
					'status' => 0,
					'msg' => "删除失败" 
			);
		}
	}
	
	/**
	 * 设为默认地址
	 *
	 * @param int $id        	
	 */
	public function setdefault($id, $uid) {
		$whereArr = array (
				'Id' => $id 
		);
		$rst1 = $this->clerdefault ( $uid );
		$rst2 = $this->where ( $whereArr )->setField ( 'IsDefault', 1 );
		if ($rst1 && $rst2) {
			return array (
					'status' => 1,
					'msg' => "设置成功" 
			);
		} else {
			return array (
					'status' => 0,
					'msg' => "设置失败" 
			);
		}
	}
}

?>