<?php

namespace Usercenter\Controller;

use Think\Controller;
use Usercenter\Model\userModel;
use Usercenter\Model\view_user_info_avatarModel;

/**
 * 基础控制器
 *
 * @author NENER
 *        
 */
class BaseController extends Controller {
	/**
	 * 检测登录
	 * 
	 * @author NENER
	 */
	public function _initialize() {
		$model = new userModel ();
		$rst = $model->islogin ( null, false, false );
		if ($rst) {
			$m = new view_user_info_avatarModel ();
			$usermodel = $m->getinfo ();
			if ($usermodel ['status'] == 1) {
				$usermodel = $usermodel ['msg'];
			} else {
				$usermodel = null;
			}

		}
		$this->assign ( 'usermodel', $usermodel );
				/* 商品图路径 */
		$this->assign ( 'gmpath', C ( 'GOODS_IMG_PATH' ) );
		/* 用户头像路径 */
		$this->assign ( 'uapath', C ( 'USER_AVATAR_PATH' ) );
	}
}
?>