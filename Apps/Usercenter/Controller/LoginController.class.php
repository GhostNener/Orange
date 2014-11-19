<?php

namespace Usercenter\Controller;
use Usercenter\Model\userModel;

/**
 * 登陆检测控制器
 *
 * @author NENER
 *        
 */
class LoginController extends BaseController {
	/**
	 * 检测登录
	 * 
	 * @author NENER
	 */
	public function _initialize() {
		parent::_initialize();
		$sid = I ( 'sid' );
		if ($sid) {
			session_id ( $sid );
			session_start ();
			$arr ['_uid'] = I ( 'cid' );
			$arr ['_key'] = I ( 'ckey' );
		}
		$model = new userModel ();
		$rst = $model->islogin ( null, false, false );
		if (! $rst) {
			redirect ( U ( '/u/login', array (
					'isadmin' => false 
			) ) );
		}
	}
}
?>