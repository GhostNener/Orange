<?php

namespace Admin\Controller;

/**
 * 礼品管理
 *
 * @author Cinwell
 *        
 */
class UserController extends BaseController {
	
	public function index() {

		$model = M ( 'user' );
		
		if (I('nick')) {
			$map['Nick'] =  array('like', "%".I('nick')."%" );
		}

		// 总数
		$allCount = $model->where ( $map )->count ();
		// 分页
		$Page = new \Think\Page ( $allCount, 10 );
		
		$showPage = $Page->show ();
		// 分页查询
		$list = $model->where ( $map )->order('LastLoginTime desc')->limit ( $Page->firstRow . ',' . $Page->listRows )->select ();

		$this->assign ( 'list', $list );
		$this->assign ( 'page', $showPage );
		$this->display ();
	}

}