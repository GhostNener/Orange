<?php

namespace Admin\Controller;

use Think\Controller;
use Usercenter\Model\userModel;
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
	}
}