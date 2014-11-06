<?php

namespace Api\Controller;

use Home\Model\view_goods_listModel;
use Home\Model\goods_categoryModel;
use Home\Model\activityModel;

/**
 * 首页控制器
 *
 * @author NENER
 *        
 */
class IndexController extends BaseController {
	/**
	 * 获取首页信息
	 */
	public function index() {
		$limit = 10;
		$model = new view_goods_listModel ();
		/* 获得最新 */
		$newlist = $model->getlist ( array (
				'Status' => 10 
		), $limit );
		$newlist = $newlist ['list'];
		/* 获得分类 */
		$model = new goods_categoryModel ();
		$clist = $model->getall ();
		/* 获取活动图片 */
		$model = new activityModel ();
		$activitylist = $model->getlist ();
		/* 返回结果 */
		echo json_encode ( array (
				'status' => 1,
				'msg' => 'ok',
				'goodslist' => $newlist,
				'actlist' => $activitylist,
				'catelist' => $clist 
		) );
	}
	/**
	 * 商品翻页
	 */
	public function goodslist() {
		$limit = 10;
		$model = new view_goods_listModel ();
		/* 获得最新 */
		$newlist = $model->getlist ( array (
				'Status' => 10 
		), $limit );
		$newlist = $newlist ['list'];
		if (! $newlist) {
			echo json_encode ( array (
					'status' => 0,
					'msg' => '没有了',
					'goodslist' => null 
			) );
		} else {
			echo json_encode ( array (
					'status' => 1,
					'msg' => 'ok',
					'goodslist' => $newlist 
			) );
		}
	}
	/**
	 * 获取商品 详情 及评论
	 */
	public function showgoods() {
		$arr = file_get_contents ( "php://input" );
		$arr = json_decode ( $arr, true );
		if (! $arr) {
			$arr = I ( 'param.' );
		}
		$Id = $arr ['Id'];
		$model = new view_goods_listModel ();
		$arr = $model->getgoodsdetails ( $Id );
		if (! $arr || ! $arr ['goods']) {
			echo json_encode ( array (
					'status' => 0,
					'msg' => '商品不存在或已下架' 
			) );
		} else {
			echo json_encode ( array (
					'status' => 1,
					'msg' => 'ok',
					'goods' => $arr ['goods'],
					'imglist' => $arr ['goodsimg'],
					'commentlist' => $arr ['commentlist'] 
			) );
		}
	}
}