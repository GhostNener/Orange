<?php

namespace Home\Controller;

use Home\Model\goods_categoryModel;
use Home\Model\activityModel;
use Usercenter\Model\user_addressModel;

/**
 * 礼品页面初始化
 *
 * @author Cinwell
 *        
 */
class GiftController extends BaseController {
	public function index()
	{
		$model = M ( 'Gift' );
		// 查询条件
		$wherrArr = array (
				'Status' => 10 
		);
		
		// 总数
		$allCount = $model->where ( $wherrArr )->count ();
		// 分页
		$Page = new \Think\Page ( $allCount, 18 );
		
		$showPage = $Page->show ();
		// 分页查询
		$list = $model->where ( $wherrArr )->order('CreateTime desc')->limit ( $Page->firstRow . ',' . $Page->listRows )->select ();

		$this->assign ( 'list', $list );
		$this->assign ( 'page', $showPage );

		$this->assign ( 'empty', '<h1 class="text-center text-import">暂无礼品</h1>' );

		$this->getheomecommon ();
		$this -> display();
	}

	private function getheomecommon() {
		/* 分类复制 */
		$model = new goods_categoryModel ();
		$clist = $model->getall ();
		$this->assign ( 'clist', $clist );
		/* 获取活动图片 */
		$model = new activityModel ();
		$hotactivitylist = $model->getlist ( array (
				'Status' => 10,
				'IsHot' => 1 
		), 3 );
		$this->assign ( 'hotactivity', $hotactivitylist );
		$this->assign ( 'emptyact', '<hr><h5 class="text-center text-import">暂无活动</h5>' );
	}

	public function exchange()
	{
		$giftid = I('giftid');
		$this -> success('兑换成功,请到消息中心查看详情');
	}

	public function getinfo()
	{
		$m = new user_addressModel ();
		$alist = $m->getall ( cookie ( '_uid' ) );
		echo json_encode ( array (
						'status' => 1,
						'data' => $alist 
		) );
	}
}