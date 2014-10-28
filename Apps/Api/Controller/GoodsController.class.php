<?php

namespace Api\Controller;

use Home\Model\goodsModel;
use Home\Model\goods_categoryModel;
use Usercenter\Model\user_addressModel;
use Home\Model\goods_serviceModel;
use Home\Model\goods_imgModel;

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
		$rst = $model->savegoods ( $postarr );
		echo json_encode ( $rst );
		return;
	}
	/**
	 * 上传商品图片
	 *
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
		if (empty($_FILES)) {
			$rstmsg ['msg'] = '空文件';
			echo json_encode ( $rstmsg );
			return;
		}
		$postarr = I ( 'param.' );
		$uid=I('_uid');
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
		$model = new goods_imgModel();
		$rst = $model->delimg ( ( int ) $arr ['imgid'] );
		echo json_encode ( $rst );
		return;
	}
}