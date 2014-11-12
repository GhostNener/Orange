<?php

namespace Home\Controller;
/**
 * 七牛上传
 *
 * @author Cinwell
 *        
 */
class QiniuController extends Controller {

	public function uploadimg(){

		$model = M("goods_img");
		$data = array (
				'GoodsId' => 0,
				'URL' => I('post.key'),
				'Title' => '',
				'Status' => 0 
		);
		$imgid = $model->create ( $data );
		$imgid = $model->add ( $imgid );
		if ($imgid) {
			return array (
					'status' => 1,
					'imgid' => $imgid,
					'msg' => C('UPLOAD_SITEIMG_QINIU')['domain'] . I('post.key') . '-320x160'
			);
		} else {
			return array (
					'status' => 0,
					'imgid' => 0,
					'msg' => '上传失败' 
			);
		}
	}

	public function uploadify()
	{
		$rst = $this->uploadimg ();

		if (( int ) $rst ['status'] == 0) {
			echo json_encode ( array (
					0,
					$rst ['msg'] 
			) );
		} else {
			echo json_encode ( array (
					$rst ['imgid'],
					$rst ['msg'] 
			) );
		}
	}
	
}