<?php

namespace Usercenter\Model;

use Think\Model;

/**
 * 查询用户信息
 */
class view_user_info_avatarModel extends Model {
	/**
	 * 获取个人信息
	 * @return array:status,msg  */
	public function getinfo() {
		$id = cookie ( '_uid' );
		if (! $id) {
			return array (
					'status' => 0,
					'msg' => '没有登录' 
			);
		}
		$model = $this->where ( array (
				'Id' => $id 
		) )->find ();
		return array (
				'status' => 1,
				'msg' => $model 
		);
	}
}