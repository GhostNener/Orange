<?php

namespace Usercenter\Controller;

use Usercenter\Model\user_addressModel;
use Usercenter\Model\userModel;
use Usercenter\Model\view_user_info_avatarModel;
class AddressController extends BaseController {
	
	/**
	 * 自动验证
	 */
	public function _initialize() {
		parent::_initialize ();
		$user = new userModel ();
		$usermodel = null;
		if ($user->islogin ( null, false, false )) {
			$m = new view_user_info_avatarModel();
			$usermodel = $m->getinfo ();
			if($usermodel['status']==1){
				$usermodel=$usermodel['msg'];
			}else{
				$usermodel=null;
			}
		}
		$this->assign ( 'usermodel', $usermodel );
	}
	/**
	 * 添加地址
	 *
	 * @author NENER
	 */
	public function addaddress() {
		$this->assign ( 'modif', 'add' )->display ( 'Index/modifaddress' );
	}
	/**
	 * 保存地址
	 *
	 * @author NENER
	 */
	public function saveaddress() {
		$arr = I ( 'post.' );
		$arr ['UserId'] = cookie ( '_uid' );
		$model = new user_addressModel ();
		$rst = $model->saveone ( $arr );
		if (( int ) $rst ['status'] == 1) {
			$this->success ( $rst ['msg'] );
		} else {
			$this->error ( $rst ['msg'] );
		}
	}
}
?>