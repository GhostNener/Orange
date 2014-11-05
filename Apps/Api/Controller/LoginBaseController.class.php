<?php

namespace Api\Controller;

use Usercenter\Model\userModel;

/**
 * 基础登录检测控制器
 *
 * @author NENER
 *        
 */
class LoginBaseController extends BaseController {
	/**
	 * 检测登录
	 */
	public function _initialize() {
		parent::_initialize ();
		$arr = api_get_login_arr();
		$model = new userModel ();
		$rst = $model->islogin ( $arr, false, true );
		if (! $rst) {
			echo json_encode ( array (
					'status' => -1,
					'msg' => '用户未登录' 
			) );
			exit ();
		}
	}
}
?>