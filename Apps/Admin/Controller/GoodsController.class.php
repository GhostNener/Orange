<?php

namespace Admin\Controller;

/**
 * 商品管理
 *
 * @author Cinwell
 *        
 */
class GoodsController extends BaseController {
	public function index() {

		$model = M ( 'view_goods_list' );
		// 查询条件
		$wherrArr = array (
				'Status' => I('status')?I('status'):10
		);
		
		// 总数
		$allCount = $model->where ( $wherrArr )->count ();
		// 分页
		$Page = new \Think\Page ( $allCount, 10 );
		
		$showPage = $Page->show ();
		// 分页查询
		$list = $model->where ( $wherrArr )->order('CreateTime desc')->limit ( $Page->firstRow . ',' . $Page->listRows )->select ();

		$this->assign ( 'list', $list );
		$this->assign ( 'page', $showPage );
		$this->display ();
	}

}