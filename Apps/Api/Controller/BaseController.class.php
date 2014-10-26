<?php

namespace Api\Controller;

use Think\Controller;
use Api\Model\appkeyModel;

/**
 * api 底层基础控制器
 *
 * @author NENER
 *        
 */
class BaseController extends Controller {
	/**
	 * api key 检测
	 */
	public function _initialize() {
		$arr = file_get_contents ( "php://input" );
		$arr = json_decode ( $arr, true );
		if (! $arr) {
			$arr = I ( 'param.' );
		}
		$key = $arr ['APPKEY'];
		$appkey = new appkeyModel ();
		if (! $appkey->checkkey ( $key )) {
			echo json_encode ( array (
					'status' => 0,
					'msg' => '没有API访问权限！'
			) );
			exit ();
		}
	}
}
?>