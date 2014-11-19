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

	public function update() {
		$id = ( int ) I ( 'get.Id' );
		if (! $id) {
			$this->error ( "页面不存在" );
		}
		$whereArr = array (
				'Id' => $id 
		);
		$dal = M ();
		$dal->startTrans (); // 开始事务
		$model = M ( 'goods' );
		$data = $model->where($whereArr)->find();
		$model->Status = I('get.status');

		if ((int)I('get.status') == 50) {
			
			CSYSN($data['UserId'],'商品下架通知',"您发布的商品 " .$data['Title']." 已被管理员下架。 原因: " . I('note'));
			
		}

		if ((int)I('get.status') == 10) {
			
			CSYSN($data['UserId'],'商品上架通知',"您发布的商品 " .$data['Title']." 通过审核，可正常查看。 ");
			
		}

		$r1 = $model->where ( $whereArr )->save (); // 操作1
		
		if ($r1) { // 成功
			$dal->commit (); // 提交事务
			$this->success ( "操作成功" );
			
		} else {
			$dal->rollback (); // 否则回滚
			$this->error ( "操作失败" );
		}
	}

	public function clear() {
		
	}

	public function recycle()
	{
		$model = M ( 'view_goods_list' );
		// 查询条件
		$wherrArr = array (
				'Status' => I('status')
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