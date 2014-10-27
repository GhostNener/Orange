<?php

namespace Usercenter\Controller;

use Usercenter\Model\user_addressModel;

class AddressController extends BaseController {
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
		$arr ['_uid'] = cookie ( '_uid' );
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