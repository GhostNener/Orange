<?php

namespace Usercenter\Model;

use Think\Model;

/**
 * 用户头像模型
 *
 * @author DongZ
 *        
 */
class user_avatarModel extends Model {
	
	/**
	 * 用户模型自动完成
	 *
	 * @var unknown
	 */
	protected $_auto = array ();
	
	/**
	 * 激活之后添加一默认头像
	 *
	 * @param unknown $uid        	
	 * @return Ambigous <\Think\mixed, boolean, string, unknown>
	 */
	public function adddefault($uid) {
		$data = array (
				'UserId' => $uid,
				'IsSysDef' => 1,
				'URL' => 'USER',
				'Status' => 10 
		);
		return $this->add ( $data );
	}
	/**
	 * 添加头像
	 *
	 * @param string $filename
	 *        	文件名
	 * @param string $uid        	
	 * @return boolean
	 */
	public function addone($filename, $uid = null) {
		if (! $uid) {
			$uid = cookie ( '_uid' );
		}
		$dal = M ();
		$dal->startTrans ();
		$am = $this->where ( array (
				'UserId' => $uid 
		) )->find ();
		$r1 = $this->where ( array (
				'UserId' => $uid 
		) )->delete ();
		if(!$am){
			$r1=1;
		}else{
			
		}
		$r2 = $this->add ( array (
				'UserId' => $uid,
				'IsSysDef' => 0,
				'URL' => $filename,
				'Status' => 10 
		) );
		if (! $r1 || ! $r2) {
			$dal->rollback ();
			return false;
		} else {
			$dal->commit ();
			return true;
		}
	}
}
?>