<?php

namespace Admin\Controller;

/**
 * 资讯管理
 *
 * @author Cinwell
 *        
 */
class LogsController extends BaseController {
	
	public function index() {

		$model = M ( 'Logs' );
		// 查询条件
		$map['UserName'] = I('UserName');
		$map['Action'] = array('LIKE', I('Action') );
		$map['Type'] = I('Type');
		
		// 总数
		$allCount = $model->where ( $map )->count ();
		// 分页
		$Page = new \Think\Page ( $allCount, 20 );
		
		$showPage = $Page->show ();
		// 分页查询
		$list = $model->where ( $map )->limit ( $Page->firstRow . ',' . $Page->listRows )->select ();

		$this->assign ( 'list', $list );
		$this->assign ( 'page', $showPage );
		$this->display ();
	}

}