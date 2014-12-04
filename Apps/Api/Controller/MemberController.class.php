<?php

namespace Api\Controller;

use Usercenter\Model\user_addressModel;
use Usercenter\Model\userModel;
use Usercenter\Model\view_user_info_avatarModel;

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
				'address' => $list,
				'msg' => 'ok' 
		) );
	}
	/**
	 * 获得单个地址（通过地址Id）
	 *
	 * @param
	 *        	Id，地址Id
	 */
	public function getoneaddress() {
		$m = new user_addressModel ();
		$arr = file_get_contents ( 'php://input' );
		$arr = json_decode ( $arr, true );
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
					'msg' => 'ok',
					'address' => $r 
			) );
			return;
		}
	}
	/**
	 * 保存地址
	 *
	 * @param
	 *        	Id：地址Id（添加不用赋值）,Tel：电话（必填）,QQ：qq,Address：地址（必填），Contacts：联系人（必填）,IsDefault[0,1]：是否是默认（0或1）,modif[add,update]：更新还是添加（必须带）
	 *        	
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
		$arr = json_decode ( $arr, true );
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
	 *
	 * @param
	 *        	Id，地址Id
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
		$arr = json_decode ( $arr, true );
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
	/**
	 * 用户签到
	 * 2014-11-23
	 */
	public function clockin() {
		$u = new userModel ();
		$r = $u->clockin ( api_get_uid () );
		echo json_encode ( $r );
	}
	/**
	 * 支付密码校验
	 * 2014-11-23
	 *
	 * @param
	 *        	pwd
	 */
	public function checkpaypwd() {
		if (! IS_POST) {
			echo json_encode ( array (
					'status' => 0,
					'msg' => '非法访问' 
			) );
			return;
		}
		$arr = file_get_contents ( 'php://input' );
		$arr = json_decode ( $arr, true );
		if (! $arr || ! $arr ['pwd']) {
			echo json_encode ( array (
					'status' => 0,
					'msg' => '空数据' 
			) );
			return;
		}
		$m = new userModel ();
		$r = $m->checkpaypwd ( $arr ['pwd'] );
		echo json_encode ( $r );
	}
	/**
	 * 获得个人基本信息
	 * 2014-11-23
	 * @return array ststus,msg,info
	 */
	public function getinfo() {
		$u = new view_user_info_avatarModel();
		$r=$u->getinfo(api_get_uid(),true);
		echo json_encode ( $r );
	}
}