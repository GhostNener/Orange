<?php

namespace Api\Controller;

use Home\Model\goodsModel;
use Home\Model\goods_categoryModel;
use Usercenter\Model\user_addressModel;
use Home\Model\goods_serviceModel;
use Home\Model\goods_imgModel;
use Home\Model\goods_commentModel;
use Home\Model\goods_orderModel;

/**
 * 个人商品管理api
 *
 * @author NENER
 *        
 */
class GoodsController extends LoginBaseController {
	/**
	 * 初始化添加
	 *
	 * @return array:status,category,service,address
	 *
	 */
	public function add() {
		$arr = file_get_contents ( "php://input" );
		$arr = json_decode ( $arr, true );
		if (! $arr) {
			$arr = I ( 'param.' );
		}
		$userid = $arr ['_uid'];
		/* 分类 */
		$cate = new goods_categoryModel ();
		$clist = $cate->getall ();
		/* 地址 */
		$add = new user_addressModel ();
		$alist = $add->getall ( $userid );
		/* 服务 */
		$ser = new goods_serviceModel ();
		$slist = $ser->getall ();
		echo json_encode ( array (
				'status' => 1,
				'category' => $clist,
				'service' => $slist,
				'address' => $alist 
		) );
	}
	
	/**
	 * 获得tk
	 */
	public function token() {
		$m = new \qiniu ();
		$config = C ( 'UPLOAD_SITEIMG_QINIU' );
		$config ['driverConfig'] ['CallbackBody'] = 'key=$(key)&APPKEY=$(x:APPKEY)&goodsid=$(x:goodsid)&_key=$(x:_key)&_uid=$(x:_uid)';
		$tk = $m->GetToken ( U ( 'callback' ), $config );
		echo json_encode ( array (
				'token' => $tk 
		) );
	}
	
	/**
	 * 商品上传 七牛 回调
	 *
	 * @param
	 *        	key 表示文件名， 自己拼。格式如：时间戳+下划线+五位随机字符
	 *        	token 表示七牛认证码： 通过api获取，`[IP]/Api/Goods/token`
	 *        	file 为文件
	 *        	goodsid, APPKEY, _key, _uid 都要带`x:` 例如`x:goodsid `, 值还是原来的值
	 * @return array：status，msg，goodsid，imgid
	 */
	public function callback() {
		$rstmsg = array (
				'status' => 0,
				'msg' => '非法访问',
				'goodsid' => 0,
				'imgid' => 0 
		);
		if (! IS_POST) {
			echo json_encode ( $rstmsg );
			return;
		}
		$postarr = I ( 'param.' );
		$uid = I ( '_uid' );
		$userid = $uid; // 用户id
		/* 商品Id */
		$postarr ['_gid'] = $postarr ['goodsid'];
		$qiniu = new \qiniu ();
		$rst = $qiniu->upload ( I ( 'post.key' ) );
		$postarr ['_imgid'] = $rst ['imgid'];
		$model = new goods_imgModel ();
		$rst = $model->saveimg ( $postarr, $userid );
		if (( int ) $rst ['status'] == 0) {
			echo json_encode ( $rst );
			return;
		} else {
			echo json_encode ( array (
					'status' => 1,
					'msg' => '上传成功',
					'goodsid' => ( int ) $rst ['goodsid'],
					'imgid' => ( int ) $postarr ['_imgid'] 
			) );
			return;
		}
	}
	
	/**
	 * 根据标题获得分类
	 *
	 * @return array:status,msg:分类列表
	 */
	public function getcategory() {
		$rstmsg = array (
				'status' => 0,
				'msg' => '非法访问' 
		);
		if (! IS_POST) {
			echo json_encode ( $rstmsg );
			return;
		}
		$str = file_get_contents ( "php://input" );
		$str = json_decode ( $str, true );
		if (! $str) {
			$rstmsg ['msg'] = '数据为空';
			echo json_encode ( $rstmsg );
			return;
		}
		$model = new goods_categoryModel ();
		$rst = $model->getcategory ( $str ['Title'] );
		echo json_encode ( $rst );
		return;
	}
	
	/**
	 * 保存商品
	 * imgcount：图片总数量
	 * GoodsId：商品Id，
	 * Title：标题，
	 * Price：价格，
	 * CostPrice：原价
	 * Presentation：简介，
	 * CategoryId：分类Id，
	 * AddressId：地址Id
	 * TradeWay：交易方式【1：线上，2： 先下，3：线上/线下】
	 * Server：服务Id；【多个服务Id用|分割：例如：1|2|3】
	 *
	 * @return array ,status,msg
	 */
	public function save() {
		$rstmsg = array (
				'status' => 0,
				'msg' => '非法访问',
				'goodsid' => 0,
				'imgid' => 0 
		);
		if (! IS_POST) {
			echo json_encode ( $rstmsg );
			return;
		}
		$postarr = file_get_contents ( 'php://input' );
		$postarr = json_decode ( $postarr, true );
		$model = new goodsModel ();
		$uid = api_get_uid ();
		$rst = $model->savegoods ( $postarr, $uid );
		echo json_encode ( $rst );
		return;
	}
	/**
	 * 上传商品图片
	 *
	 * @param
	 *        	key 表示文件名， 自己拼。格式如：时间戳+下划线+五位随机字符
	 *        	token 表示七牛认证码： 通过api获取，`[IP]/Api/Goods/token`
	 *        	file 为文件
	 *        	goodsid, APPKEY, _key, _uid 都要带`x:` 例如`x:goodsid `, 值还是原来的值
	 * @return array：status，msg，goodsid，imgid
	 */
	public function upload() {
		$rstmsg = array (
				'status' => 0,
				'msg' => '非法访问',
				'goodsid' => 0,
				'imgid' => 0 
		);
		if (! IS_POST) {
			echo json_encode ( $rstmsg );
			return;
		}
		if (empty ( $_FILES )) {
			$rstmsg ['msg'] = '空文件';
			echo json_encode ( $rstmsg );
			return;
		}
		$postarr = I ( 'param.' );
		$uid = I ( '_uid' );
		$userid = $uid; // 用户id
		/* 商品Id */
		$postarr ['_gid'] = $postarr ['goodsid'];
		$model = new goods_imgModel ();
		$rst = $model->uploadimg ();
		$rstmsg ['msg'] = '上传失败';
		if (( int ) $rst ['status'] == 0) {
			echo json_encode ( $rstmsg );
			return;
		}
		$postarr ['_imgid'] = $rst ['imgid'];
		$rst = $model->saveimg ( $postarr, $userid );
		if (( int ) $rst ['status'] == 0) {
			echo json_encode ( $rst );
			return;
		} else {
			echo json_encode ( array (
					'status' => 1,
					'msg' => '上传成功',
					'goodsid' => ( int ) $rst ['goodsid'],
					'imgid' => ( int ) $postarr ['_imgid'] 
			) );
			return;
		}
	}
	
	/**
	 * 删除图片
	 *
	 * @param
	 *        	imgid
	 * @return array：status，msg
	 */
	public function delimg() {
		$msg = array (
				'status' => 0,
				'msg' => '非法访问' 
		);
		if (! IS_POST) {
			echo json_encode ( $msg );
			return;
		}
		$arr = file_get_contents ( 'php://input' );
		$arr = json_decode ( $arr, true );
		if (! $arr ['imgid']) {
			$msg ['msg'] = '没有获得要删除的图片';
			echo json_encode ( $msg );
			return;
		}
		
		$model = new \qiniu ();
		$rst = $model->del ( ( int ) $arr ['imgid'] );
		echo json_encode ( $rst );
		return;
	}
	/**
	 * 添加评论
	 *
	 * @param
	 *        	array (GoodsId,Content,AssesseeId,ReplyId)
	 */
	public function savecomment() {
		$msg = array (
				'status' => 0,
				'msg' => '非法访问' 
		);
		if (! IS_POST) {
			echo json_encode ( $msg );
			return;
		}
		$arr = file_get_contents ( 'php://input' );
		$arr = json_decode ( $arr, true );
		if (! $arr) {
			$msg ['msg'] = '空数据';
			echo json_encode ( $msg );
			return;
		}
		$m = new goods_commentModel ();
		$r = $m->addComment ( $arr, api_get_uid () );
		echo json_encode ( $r );
	}
	/**
	 * 购买商品
	 * 2014-11-23
	 * 
	 * @param
	 *        	GoodsId：商品Id
	 *        	BuyerAddId：买家地址Id
	 *        	TradeWay：买家选择的交易方式（此交易方式需要根据商品提供的交易方式来创建，1=>线上，2=>线下，3=>[1,2]）
	 * @return json string status,msg,address
	 */
	public function buy() {
		$msg = array (
				'status' => 0,
				'msg' => '非法访问' 
		);
		if (! IS_POST) {
			echo json_encode ( $msg );
			return;
		}
		$arr = file_get_contents ( 'php://input' );
		$arr = json_decode ( $arr, true );
		if (! $arr) {
			$msg ['msg'] = '空数据';
			echo json_encode ( $msg );
			return;
		}
		$arr ['Code'] = date ( 'YmdHis', time () ) . $arr ['GoodsId'];
		$arr ['CreateTime'] = time ();
		$m = new goods_orderModel ();
		$rst = $m->createone ( $arr,api_get_uid() );
		if (( int ) $rst ['status'] == 0) {
			echo json_encode ( $rst );
		} else {
			logs ( '购买成功 ID' . $arr ['GoodsId'], 3 );
			echo json_encode ( $rst );
		}
	}
}