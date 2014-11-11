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
		$model = new userModel ();
		$rst = $model->islogin ( null, false, false );
		if (! $rst) {
			redirect ( U ( 'Usercenter/User/index', array (
					'isadmin' => false 
			) ) );
		}
	}
}
?>