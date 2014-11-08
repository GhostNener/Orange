<?php

namespace Home\Controller;

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
	 */
	public function _initialize() {
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
			redirect ( U ( 'Usercenter/User/index', array (
					'isadmin' => false 
			) ) );
		} else {
			$m = new view_user_info_avatarModel ();
			$usermodel = $m->getinfo ();
			if ($usermodel ['status'] == 1) {
				$usermodel = $usermodel ['msg'];
			} else {
				$usermodel = null;
			}
			$isclockin = checkclockin ();
			if ($isclockin) {
				$isclockin = 1;
			} else {
				$isclockin = 0;
			}
			$this->assign ( 'isclockin', $isclockin );
			$this->assign ( 'usermodel', $usermodel );
		}
	}
}
?>