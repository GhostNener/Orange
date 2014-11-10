<?php

namespace Usercenter\Controller;

use Think\Controller;
use Usercenter\Model\userModel;
use Usercenter\Model\view_user_info_avatarModel;

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