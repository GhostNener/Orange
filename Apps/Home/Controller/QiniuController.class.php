<?php

namespace Home\Controller;
use Think\Upload\Driver\Qiniu\QiniuStorage;
require_once './ORG/qiniu/qiniu.class.php';

use Think\Controller;

/**
 * 七牛上传
 *
 * @author Cinwell
 *        
 */
class QiniuController extends Controller {

	public function uploadify() {

		if(!I('post.key')) {
			$this->error("(╬▔皿▔)凸<br>不要乱搞");
		}

		$qiniu = new \qiniu();
		$rst = $qiniu->upload (I('post.key'));

		if (( int ) $rst ['status'] == 0) {
			echo json_encode ( array (
					0,
					$rst ['msg'] 
			) );
		} else {
			echo json_encode ( array (
					$rst ['imgid'],
					$rst ['msg'],
					$rst ['key']

			) );
		}
	}
	
}