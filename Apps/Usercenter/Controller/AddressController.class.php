<?php

namespace Usercenter\Controller;

use Usercenter\Model\user_addressModel;
use Usercenter\Model\userModel;
use Usercenter\Model\view_user_info_avatarModel;
class AddressController extends LoginController {
	
	/**
	 * 添加地址
	 *
	 * @author NENER
	 */
	public function addaddress() {
		$this->assign ( 'modif', 'add' )->display ( 'Address/modifaddress' );
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
	
	/**
	 * 渲染修改地址页面
	 *
	 * @author DongZ
	 */
	public function modifaddress($Id) {
		$model = new user_addressModel();
		$address = $model -> getbyid($Id);
		$this->assign('model',$address);
		$this->assign ( 'modif', 'update' );
		$this->display ( 'Address/modifaddress' );
	}
	
	/**
	 * 删除地址
	 *
	 * @author DongZ
	 */
	public function deladdress($Id) {
		$model = new user_addressModel();
		$rst = $model-> del($Id);
		if (( int ) $rst ['status'] == 1) {
			$this->success ( $rst ['msg'] );
		} else {
			$this->error ( $rst ['msg'] );
		}
	}
	
	/**
	 * 地址列表
	 * @author DongZ
	 */
	public function addresslist(){
		$userid= cookie('_uid');
		$model = new user_addressModel();
		$rst = $model-> getall($userid);
		$this->assign('address',$rst);
		$this->display ('UserHome/addresslist');
	}
	
	/**
	 * 设为默认地址
	 *
	 * @author DongZ
	 */
	public function setdefault($Id) {
		$uid= cookie('_uid');
		$model = new user_addressModel();
		$rst = $model-> setdefault($Id, $uid);
		if (( int ) $rst ['status'] == 1) {
			$this->success ( $rst ['msg'] );
		} else {
			$this->error ( $rst ['msg'] );
		}
	}
}
?>