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
		$modifArr = array (
				"add",
				"update" 
		);
		$modif = strtolower ( I ( 'post.modif' ) );
		if (! in_array ( $modif, $modifArr )) {
			$this->error ( "非法操作" );
		}

		$model = M ( 'activity' );
		$data = array (
				'Href' => I ( 'Href' ),
				'Title' => I ( 'Title' ),
				'Presentation' => I ( 'Presentation' ),
				'Contents' => I ( 'Contents' ),
				'IsHot' => I ( 'IsHot' ),
				'IsTop' => I ( 'IsTop' )
		);

		//处理图片
		foreach ($_FILES as $key => $value) {
			$config = C('IMG_UPLOAD_CONFIG');
			$config['saveName'] = $key.time();
			$config ['savePath'] = 'Activity/' . C ( 'GOODS_IMG_SOURCE' );

			$rstarr = uploadfile ( $config , null);
			if (!$rstarr['status']) {
				continue;
			}
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
			$model->where ( $whereArr )->save ( $data );
		}
		$this->success ( '操作成功' );
	}
}