<?php

namespace Admin\Controller;

use Think\Controller;
use Usercenter\Model\userModel;
use Usercenter\Model\view_user_info_avatarModel;

/**
 * 后台首页
 *
 * @author NENER
 *        
 */
class BaseController extends Controller {
	/**
	 * 检测登录
	 */
	public function _initialize() {
		$model=new userModel();
		$rst=$model->islogin(null,true,false);
		if(!$rst){
			redirect(U('Usercenter/User/index',array('isadmin'=>true)));
		}
		else{
			$usermodel = null;
			$m = new view_user_info_avatarModel ();
			$usermodel = $m->getinfo ();
			if ($usermodel ['status'] == 1) {
				$usermodel = $usermodel ['msg'];
			} else {
				$usermodel = null;
			}
			$this->assign ( 'usermodel', $usermodel );
		}
	}
}