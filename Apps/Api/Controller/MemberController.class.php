<?php

namespace Api\Controller;

use Usercenter\Model\user_addressModel;
use Usercenter\Model\userModel;
use Usercenter\Model\view_user_info_avatarModel;
use Usercenter\Model\view_goods_order_listModel;
use Home\Model\goods_orderModel;

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
		$r = $m->checkpaypwd ( $arr ['pwd'], api_get_uid () );
		echo json_encode ( $r );
	}
	/**
	 * 获得个人基本信息
	 * 2014-11-23
	 *
	 * @return array ststus,msg,info
	 */
	public function getinfo() {
		$u = new view_user_info_avatarModel ();
		$r = $u->getinfo ( api_get_uid (), true );
		echo json_encode ( $r );
	}
	/**
	 * 保存个人信息
	 * 2014-12-11
	 * post
	 *
	 * @param
	 *        	array RealName 真实姓名 ,Nick 昵称,Sex 性别[男，女],QQ qq,Birthday 生日（非时间戳）例如：2014-12-12
	 * @return array ststus,msg
	 *        
	 */
	public function saveinfo() {
		if (! IS_POST) {
			echo json_encode ( array (
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
		$model = new userModel ();
		$rst = $model->updateinfo ( $arr, api_get_uid () );
		echo json_encode ( $rst );
	}
	
	/**
	 * 上传头像
	 * 2014-12-11
	 * post
	 * 
	 * @param
	 *        	AURL
	 * @return array ststus,msg
	 *        
	 */
	public function upload() {
		if (! IS_POST) {
			echo json_encode ( array (
					'status' => 0,
					'msg' => '非法访问' 
			) );
			return;
		}
		if(empty($_FILES)){
			echo json_encode ( array (
					'status' => 0,
					'msg' => '空数据' 
			) );
			return;
		}
		$setting = C ( 'UPLOAD_SITEIMG_QINIU' );
		$setting ['savePath'] = 'Avatar/';
		$Upload = new \Think\Upload ( $setting );
		$info = $Upload->upload ( $_FILES );
		$filename = str_replace ( '/', '_', $info ['AURL'] ['savepath'] ) . $info ['AURL'] ['savename'];
		if (! $filename) {
			echo json_encode ( array (
					'status' => 0,
					'msg' => '上传失败:获取文件名失败！' 
			) );
			return;
		}
		$m = new user_addressModel ();
		if (! $m->addone ( $filename, api_get_uid () )) {
			echo json_encode ( array (
					'status' => 0,
					'msg' => '上传失败' 
			) );
			return;
		} else {
			echo json_encode ( array (
					'status' => 1,
					'msg' => '上传成功' 
			) );
			return;
		}
	}
	
	/**
	 * 修改密码
	 * 2014-12-11
	 * post
	 * 
	 * @param
	 *        	array Type (1,登录密码；2，支付密码),OldPassword 原密码,NewPassword 新密码,ConfirmPwd 确认密码
	 *        	
	 */
	public function changepwd() {
		if (! IS_POST) {
			echo json_encode ( array (
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
		$m = new userModel ();
		$rst = $m->changepwd ( $arr, ( int ) $arr ["Type"], api_get_uid () );
		echo json_encode ( $rst );
	}
	/**
	 * 获得未完成订单
	 * 2014-12-11
	 * get
	 * 
	 * @param
	 *        	int p
	 * @return array status,list
	 *        
	 */
	public function unfinishorder() {
		$model = new view_goods_order_listModel ();
		$arr = $model->getorder ( 1, 5, ACTION_NAME, true, null, api_get_uid () );
		unset ( $arr ['page'] );
		echo json_encode ( $arr );
	}
	/**
	 * 获得出售订单
	 * 2014-12-11
	 * get
	 * 
	 * @param
	 *        	int p 翻页
	 * @return array status,list
	 *        
	 */
	public function sellorder() {
		$model = new view_goods_order_listModel ();
		$arr = $model->getsellorder ( 1, 5, ACTION_NAME, true, null, api_get_uid () );
		unset ( $arr ['page'] );
		echo json_encode ( $arr );
	}
	/**
	 * 获得购买订单
	 * 2014-12-11
	 * get
	 * 
	 * @param
	 *        	int p 翻页
	 * @return array status,list
	 *        
	 */
	public function buyorder() {
		$model = new view_goods_order_listModel ();
		$arr = $model->getbuyorder ( 1, 5, ACTION_NAME, true, null, api_get_uid () );
		unset ( $arr ['page'] );
		echo json_encode ( $arr );
	}
	/**
	 * 发货 /收货
	 * 2014-12-11
	 * post
	 * 
	 * @param
	 *        	array Type(1,发货；2，收货) ，Id 订单Id
	 * @return array status,msg
	 *        
	 */
	public function updateorder() {
		if (! IS_POST) {
			echo json_encode ( array (
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
		$model = new goods_orderModel ();
		$rst = $model->update ( $arr ['Id'], ( int ) $arr ['Type'], api_get_uid () );
		echo json_encode ( $rst );
	}
	/**
	 * 取消交易（买家操作，基于卖家未发货的情况下）
	 * post
	 * 
	 * @param
	 *        	array Id, 订单Id
	 * @return array status,msg
	 *        
	 */
	public function canceltrade() {
		if (! IS_POST) {
			echo json_encode ( array (
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
		$model = new goods_orderModel ();
		$rst = $model->cancelorder ( $arr['Id'],api_get_uid() );
		echo json_encode($rst);
	}
	/**
	 * 评分
	 * 2014-12-11
	 * post
	 * @param array Id 订单Id,Star 分数[1-5]
	 * @return array status,msg
	 *   */
	public function marking(){
		if (! IS_POST) {
			echo json_encode ( array (
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
		$model = new goods_orderModel();
		$rst = $model->savestar ( $arr['Id'], (int)$arr['Star'],api_get_uid() );
		echo json_encode($rst);
	}
}