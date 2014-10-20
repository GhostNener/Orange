<?php

namespace Api\Controller;

use Think\Controller;
use Home\Model\goodsModel;
use Home\Model\goods_categoryModel;
use Home\Model\user_addressModel;
use Home\Model\goods_serviceModel;

/**
 * 商品api
 *
 * @author NENER
 *        
 */
class GoodsController extends Controller {
	public function index() {
	}
	/**
	 * 初始化添加
	 * @return array:status,category,service,address
	 *   */
	public function add() {
		$userid = 0;
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
				'service'=>$slist,
				'address'=>$alist
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
		$str = I ( 'Title' );
		if (! $str) {
			$rstmsg ['msg'] = '数据为空';
			echo json_encode ( $rstmsg );
			return;
		}
		$model = new goods_categoryModel ();
		$rst = $model->getcategory ( $str );
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
		$postarr = I ( 'post.' );
		$model = new goodsModel ();
		$rst = $model->save( $postarr );
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
		if (empty ( $_FILES )) {
			echo json_encode ( $rstmsg );
			return;
		}
		$userid = 0;
		$postarr = I ( 'post.' );
		/* 商品Id */
		$postarr ['_gid'] = $postarr ['goodsid'];
		$model = new goodsModel ();
		$rst = $model->uploadimg ();
		$rstmsg ['msg'] = '上传失败';
		if ((int)$rst ['status'] == 0) {
			echo json_encode ( $rstmsg );
			return;
		}
		$postarr ['_imgid'] = $rst ['imgid'];
		$rst = $model->saveimg ( $postarr, $userid );
		if ((int)$rst ['status'] == 0) {
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
		if (! I ( 'imgid' )) {
			// 没有获得要删除的图片
			echo json_encode ( $msg );
			return;
		}
		$model = new goodsModel ();
		$rst = $model->delimg ( ( int ) I ( 'imgid' ) );
		echo json_encode ( $rst );
		return;
	}
}