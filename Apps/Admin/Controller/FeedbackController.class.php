<?php

namespace Admin\Controller;

/**
 * 反馈管理
 *
 * @author Cinwell
 *        
 */
class FeedbackController extends BaseController {
	
	public function index() {

		$model = M ( 'feedback' );

		// 总数
		$allCount = $model->where ( array('Status' => 0) )->count ();
		// 分页
		$Page = new \Think\Page ( $allCount, 10 );
		
		$showPage = $Page->show ();
		// 分页查询
		$list = $model->where ( array('Status' => 0) )->order('CreateTime desc')->limit ( $Page->firstRow . ',' . $Page->listRows )->select ();

		$this->assign ( 'list', $list );
		$this->assign ( 'page', $showPage );
		$this->display ();
	}

	public function recycle() {

		$model = M ( 'feedback' );
		// 查询条件
		$wherrArr = array (
				'Status' => 1 
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

	public function reply()
	{
		$id = I('Id');
		$data['Reply'] = I('Reply');
		$data['Status'] = 1;
		$model = M('feedback');
		$r = $model->where(array('Id' => $id))->save($data);
		if ($r) {
			CSYSN(I('UserId'),'反馈信息回复','反馈内容: <blockquote><p>' . I('Contents') . '</p></blockquote>管理员回复: <blockquote><p class="text-danger">'.I('Reply') .'</p></blockquote>');
			$this->success('回复成功');
		}else{
			$this->success('保存失败');
		}

	}

	public function update()
	{
		$id = ( int ) I ( 'get.Id' );
		if (! $id) {
			$status = 0;
			$info = 'id都没有改个屁啊！';
		}
		$whereArr = array (
				'Id' => $id 
		);
		$model = M ( 'feedback' )->where ( $whereArr )->find ();
		if ($model) {

			$status = 1;
			$data = $model;

		} else {
			$status = 0;
			$info = '没成功，活该，重新再请求';
		}

		echo json_encode ( array (
						'status' => $status,
						'info' => $info,
						'data' => $data 
		) );
	}

}