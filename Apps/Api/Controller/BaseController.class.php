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
		$appkey = I ( 'get.APPKEY' );
		if (! $appkey) {
			$appkey = I ( 'post.APPKEY' );
		}
		if (! $appkey) {
			
			$arr = file_get_contents ( "php://input" );
			$arr = json_decode ( $arr, true );
			$key = $arr ['APPKEY'];
		} else {
			$key = $appkey;
		}		
		$appkey = new appkeyModel ();
		if (! $appkey->checkkey ( $key )) {
			echo json_encode ( array (
					'status' => -2,
					'msg' => '没有API访问权限！' 
			) );
			exit ();
		}
	}
}
?>