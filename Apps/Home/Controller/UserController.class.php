<?php

namespace Home\Controller;

use Think\Controller;

class UserController extends Controller {
	public function index() {
		$this->display ( 'Index/index' );
	}
	public function addaddress() {
		$this->assign ( 'modif', 'add' )->display ( 'modifaddress' );
	}
	public function saveaddress() {
		$userid = 0;
		$arr = I ( 'post.' );
		if (! IS_POST || ! $arr) {
			$this->error ( '页面不存在' );
		}
		$modifarr = array (
				'add',
				'update' 
		);
		if (! in_array ( $arr ['modif'], $modifarr )) {
			$this->error ( '非法操作' );
		}
		$data = array (
				'UserId' => $userid,
				'Tel' => $arr ['Tel'],
				'QQ' => $arr ['QQ'],
				'Address' => $arr ['Address'],
				'IsDefault' => $arr ['IsDefault'],
				'Status' => 10 
		);
		$dal = M ();
		$dal->startTrans ();
		$rst2 = 1;
		// 首先判断是不是设置的默认地址
		if (( int ) $arr ['IsDefault'] == 1) {
			// 先修改其他默认地址为非默认状态
			if (M ( 'user_address' )->where ( array (
					'UserId' => $userid,
					'IsDefault' => 1 
			) )->count ()) {
				$rst2 = M ( 'user_address' )->where ( array (
						'UserId' => $userid 
				) )->save ( array (
						'IsDefault' => 0 
				) );
			}
		}
		// 添加
		if ($arr ['modif'] == 'add') {
			$rst1 = M ( 'user_address' )->data ( $data )->add ();
		} else {
			// 保存
			$rst1 = M ( 'user_address' )->where ( array (
					'Id' => ( int ) $arr ['Id'] 
			) )->save ( $data );
		}
		if ($rst1 && $rst2) {
			$dal->commit ();
			$this->success ( '操作成功' );
		} else {
			$dal->rollback ();
			$this->error ( '操作失败' );
		}
	}
}