<?php

namespace Admin\Controller;

/**
 * 后台首页
 *
 * @author NENER
 *        
 */
class ActivityController extends BaseController {
	public function index() {
			$model = M ( 'activity' );
		// 查询条件
		$wherrArr = array (
				'Status' => 10 
		);
		// 总数
		$allCount = $model->where ( $wherrArr )->count ();
		// 分页
		$Page = new \Think\Page ( $allCount, 10 );
		
		$showPage = $Page->show ();
		// 分页查询
		$list = $model->where ( $wherrArr )->limit ( $Page->firstRow . ',' . $Page->listRows )->select ();

		$this->assign ( 'list', $list );
		$this->assign ( 'page', $showPage );
		$this->display ();
	}

	public function save()
	{
		# code...
	}
}