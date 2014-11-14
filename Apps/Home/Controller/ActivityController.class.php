<?php
namespace Home\Controller;
use Home\Model\activityModel;

/**
* 活动（发现页面）
* @author Cinwell
*/
class ActivityController extends BaseController {
	
	public function index()
	{
		$model = new activityModel ();
		$list = $model -> getlist();
		$page = $model -> getpage();

		$this->assign ( 'empty', '<h1 class="text-center text-import">暂无礼品</h1>' );
		$this->assign ( 'list', $list );
		$this->assign ( 'page', $page );
		$this -> display();
		
	}

	public function detail()
	{
		$model = new activityModel ();
		$data = $model->getdetail(I('id'));
		$this->assign ( 'data', $data );
		$this -> display();
	}
}