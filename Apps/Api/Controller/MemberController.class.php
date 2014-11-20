<?php

namespace Api\Controller;

use Usercenter\Model\user_addressModel;

/**
 * 个人中心api
 *
 * @author NENER
 *        
 */
class MemberController extends LoginBaseController {
	
	/**
	 * 获取个人的所有可用地址
	 */
	public function getalladdress() {
		$m = new user_addressModel ();
		$list = $m->getall ( api_get_uid () );
		if (! $list) {
			echo json_encode ( array (
					'status' => 0,
					'msg' => '哎呀，没有可用地址' 
			) );
			return;
		}
		echo json_encode ( array (
				'status' => 1,
				'address' => $list ,
				'msg'=>'ok'
		) );
	}
	/**
	 * 获得单个地址（通过地址Id）
	 */
	public function getoneaddress() {
		$m = new user_addressModel ();
		$arr = file_get_contents ( 'php://input' );
		$arr = json_encode ( $arr, true );
		if (! $arr || ! $arr ['Id']) {
			echo json_encode ( array (
					'status' => 0,
					'msg' => '空数据' 
			) );
			return;
		}
		$r = $m->getbyid ( ( int ) $arr ['Id'], api_get_uid () );
		if (! $r) {
			echo json_encode ( array (
					'status' => 0,
					'msg' => '获取失败' 
			) );
			return;
		} else {
			echo json_encode ( array (
					'status' => 1,
					'msg' => 'ok' ,
					'address'=>$r
			) );
			return;
		}
	}
	/**
	 * 保存地址
	 * Id,Tel,QQ,Address,IsDefault[0,1],modif[add,update],Contacts
	 */
	public function saveaddress() {
		if (! IS_POST) {
			echo json_decode ( array (
					'status' => 0,
					'msg' => '非法访问' 
			) );
			return;
		}
		
		$arr = file_get_contents ( 'php://input' );
		$arr = json_encode ( $arr, true );
		if (! $arr) {
			echo json_encode ( array (
					'status' => 0,
					'msg' => '空数据' 
			) );
			return;
		}
		$m = new user_addressModel ();
		$r = $m->saveone ( $arr, api_get_uid () );
		echo json_encode ( $r );
	}
	/**
	 * 删除地址
	 */
	public function deladdress() {
		if (! IS_POST) {
			echo json_decode ( array (
					'status' => 0,
					'msg' => '非法访问' 
			) );
			return;
		}
		$arr = file_get_contents ( 'php://input' );
		$arr = json_encode ( $arr, true );
		if (! $arr || ! $arr ['Id']) {
			echo json_encode ( array (
					'status' => 0,
					'msg' => '空数据' 
			) );
			return;
		}
		$m = new user_addressModel ();
		$r = $m->del ( ( int ) $arr ['Id'], api_get_uid () );
		echo json_encode ( $r );
	}
}