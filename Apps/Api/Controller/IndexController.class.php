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
}