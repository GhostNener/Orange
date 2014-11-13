<?php
use Think\Upload\Driver\Qiniu\QiniuStorage;
class qiniu {
	
	/**
	 * 获得七牛token
	 *
	 * @param
	 *        	回调地址
	 * @return token
	 */
	function GetToken($callback = "") {
		$config = C ( 'UPLOAD_SITEIMG_QINIU' );
		$config = $config ['driverConfig'];
		$callback = $callback != "" ? $callback : U ( 'callback' );
		$config ['CallbackUrl'] = 'http://' . $_SERVER ['HTTP_HOST'] . $callback;
		$qiniu = new QiniuStorage ( $config );
		$token = $qiniu->UploadToken ( $config ['secrectKey'], $config ['accessKey'], $config );
		return $token;
	}
	
	/**
	 * 获得图片文件URL
	 *
	 * @param
	 *        	文件名
	 * @param
	 *        	样式 120x60, 320x160, 800x300, 800x800
	 * @return token
	 */
	function GetFileUrl($file, $type) {
		$separator = C ( 'FILE_SIZE_SEPARATOR' );
		$config = C ( 'UPLOAD_SITEIMG_QINIU' );
		$config = $config ['driverConfig'];
		
		$fileUrl = 'http://' . $config ['domain'] . '/' . $file . $separator . $type;
		return $fileUrl;
	}
	function upload($key) {
		$model = M ( "goods_img" );
		$data = array (
				'GoodsId' => 0,
				'URL' => $key,
				'Title' => '',
				'Status' => 0 
		);
		$imgid = $model->create ( $data );
		$imgid = $model->add ( $imgid );
		
		if ($imgid) {
			return array (
					'status' => 1,
					'imgid' => $imgid,
					'msg' => $this->GetFileUrl ( $key, '120x60' ),
					'key' => $key 
			);
		} else {
			return array (
					'status' => 0,
					'imgid' => 0,
					'msg' => '上传失败' 
			);
		}
	}
	function del($key) {
		$config = C ( 'UPLOAD_SITEIMG_QINIU' );
		$config = $config ['driverConfig'];
		
		$qiniu = new QiniuStorage ( $config );
		$result = $qiniu->del ( $key );
		// 删除成功不返回结果
		if (! $result) {
			// 删除成功就删除数据库数据
			$model = M ( 'goods_img' );
			$result = $model->where ( array (
					'URL' => $key 
			) )->delete ();
			if (! $result) {
				$status = 0;
				$msg = '数据库删除失败';
			} else {
				$status = 1;
				$msg = '数据库删除成功';
			}
		} else {
			$status = 0;
			$msg = '服务器删除失败';
		}
		
		return array (
				'status' => $status,
				'msg' => $msg 
		);
	}
}