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
}
?>