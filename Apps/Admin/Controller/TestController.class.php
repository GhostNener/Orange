<?php

namespace Admin\Controller;
use Think\Upload\Driver\Qiniu\QiniuStorage;

/**
 * 礼品管理
 *
 * @author Cinwell
 *        
 */
class TestController extends BaseController {
	public function index() {
		$setting=C('UPLOAD_SITEIMG_QINIU');
		$config = $setting['driverConfig'];
		$config['CallbackUrl'] = $_SERVER['HTTP_HOST'] . U('callback');
		$qiniu = new QiniuStorage($config);
		$token = $qiniu->UploadToken($config['secrectKey'],$config['accessKey'],$config);
		$this->assign ( 'token', $token );

		$this->display ();
	}

	public function save()
	{

		//判断文件是否为空
		if ($_FILES) {
			$config = C('IMG_UPLOAD_CONFIG');
			$config['saveName'] = 'gift'.time();
			$config ['savePath'] = 'Activity/' . C ( 'GOODS_IMG_SOURCE' );

			$rstarr = uploadfile ( $config , null);
		}

		var_dump($rstarr);
		return;
	}

	public function callback()
	{
		$key = I('key');
		$arr = array('haha' => $key );
		echo json_encode($arr);
	}

	public function qiniu()
	{
		$setting=C('UPLOAD_SITEIMG_QINIU');
		$Upload = new \Think\Upload($setting);
		$info = $Upload->upload($_FILES);
		var_dump($info);
		return;
	}

	

	
}