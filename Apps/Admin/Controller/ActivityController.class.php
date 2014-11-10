<?php

namespace Admin\Controller;

/**
 * 资讯管理
 *
 * @author Cinwell
 *        
 */
class ActivityController extends BaseController {
	public function index() {

		$model = M ( 'activity' );
		// 查询条件
		$wherrArr = array (
				'Status' => 10 
		);
		
		$type = I('type');
		if ($type=='hot') {
			$wherrArr['IsHot'] = 1;
		} else if($type=='top'){
			$wherrArr['IsTop'] = 1;
		}
		
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

	public function recycle() {

		$model = M ( 'activity' );
		// 查询条件
		$wherrArr = array (
				'Status' => -1 
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
		if (! IS_POST) {
			$this->error ( "页面不存在" );
		}
		C('DEFAULT_FILTER','htmlspecialchars');

		$modifArr = array (
				"add",
				"update" 
		);
		$modif = strtolower ( I ( 'post.modif' ) );
		if (! in_array ( $modif, $modifArr )) {
			$this->error ( "非法操作" );
		}

		$model = M ( 'activity' );

		//富文本内容
		$data = array (
				'Href' => I ( 'Href' ),
				'Title' => I ( 'Title' ),
				'Presentation' => I ( 'Presentation' ),
				'Contents' => I ( 'Contents' ),
				'IsHot' => I ( 'IsHot' ),
				'IsTop' => I ( 'IsTop' )
		);

		C('DEFAULT_FILTER','htmlspecialchars,strip_tags');

		//处理图片
		foreach ($_FILES as $key => $value) {

			//判断文件是否为空
			if ($value['size']<=0) {
				continue;
			}

			$config = C('IMG_UPLOAD_CONFIG');
			$config['saveName'] = $key.time();
			$config ['savePath'] = 'Activity/' . C ( 'GOODS_IMG_SOURCE' );

			$rstarr = uploadfile ( $config , null);

			$srcpath = $config['rootPath'].$rstarr['msg'][$key]['savepath'].$rstarr['msg'][$key]['savename'];

		 	if ($key=='ImgURL') {
		 		$savepath = $config['rootPath'].'Activity/800_300/'.time().'.jpg';
		 		cutimg($srcpath,$savepath,array(800,300),2);
		 	}
		 	else{
		 		$savepath = $config['rootPath'].'Activity/80_80/'.time().'.jpg';
		 		cutimg($srcpath,$savepath,array(80,80),2);
		 	}
		 	unlink ( $srcpath );
			$data[$key] = substr($savepath, 1);
		}

		if ($modif == "add") {

			$data ['Status'] = 10;
			$data ['CreateTime'] = time();
			$dal = M ();
			$dal->startTrans ();
			$r1 = $model->data ( $data )->add ();
			if ($r1) {
				$dal->commit ();
			} else {
				$dal->rollback ();
				$this->error ( "操作失败" );
			}
		} else {
			$whereArr = array (
					'Id' => ( int ) I ( "post.Id" ) 
			);

			//如果更新了图片，先删除以前的旧图
			$oldmodel = $model->where ( $whereArr )->find();
			if ($data['ImgURL']) {
				unlink('.' . $oldmodel['ImgURL']);
			}
			 if($data['ThumURL']) {
				unlink('.' . $oldmodel['ThumURL']);
			}

			$model->where ( $whereArr )->save ( $data );
		}
		$this->success ( '操作成功' );
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
		$model = M ( 'activity' )->where ( $whereArr )->find ();
		if ($model) {

			$model['Contents'] = htmlspecialchars_decode($model['Contents']);
			$status = 1;
			$data = $model;
			$method = 'update';

		} else {
			$status = 0;
			$info = '没成功，活该，重新再请求';
		}

		echo json_encode ( array (
						'status' => $status,
						'info' => $info,
						'method' => $method,
						'data' => $data 
		) );
	}

	//软删除
	public function del()
	{
		$id = ( int ) I ( 'get.Id' );
		if (! $id) {
			$this->error ( "页面不存在" );
		}
		$whereArr = array (
				'Id' => $id 
		);
		$dal = M ();
		$dal->startTrans (); // 开始事务
		$model = M ( 'activity' );
		$model->Status = - 1;
		$r1 = $model->where ( $whereArr )->save (); // 操作1
		
		if ($r1) { // 成功
			$dal->commit (); // 提交事务
			$this->success ( "操作成功" );
			
		} else {
			$dal->rollback (); // 否则回滚
			$this->error ( "操作失败" );
		}
	}

	//硬删除
	public function clear()
	{
		$id = ( int ) I ( 'Id' );
		
		$whereArr = array (
				'Id' => $id 
		);

		if (! $id) {
			$whereArr = array (
				'Status' => -1
			);
		}

		$model = M ( 'activity' );
		
		$list = $model->where ( $whereArr )->select();
		foreach ($list as $key => $value) {
			unlink("." . $value['ImgURL']);
			unlink("." . $value['ThumURL']);
		}
		if ($model->where ( $whereArr )->delete ()) {

			$this->success ( '操作成功' );
		}else{
			$this->error ( "操作失败" );
		}
	}
}