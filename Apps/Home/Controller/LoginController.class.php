<?php

namespace Home\Controller;

use Usercenter\Model\userModel;

/**
 * 检测登录控制器
 *
 * @author NENER
 *        
 */
class LoginController extends BaseController {
	/**
	 * 检测登录
	 */
	public function _initialize() {
		parent::_initialize();
		/* FF302解决 */
		$sid = I ( 'sid' );
		if ($sid) {
			session_id ( $sid );
			session_start ();
			$arr ['_uid'] = I ( 'cid' );
			$arr ['_key'] = I ( 'ckey' );
		}
		$model = new userModel ();
		if (! $arr) {
			$rst = $model->islogin ( null, false, false );
		} else {
			$rst = $model->islogin ( $arr, false, true );
		}
		if (! $rst) {
			redirect ( U ( '/u/login', array (
					'isadmin' => false 
			) ) );
		}
	}
}
?>