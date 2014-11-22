<?php

namespace Home\Controller;

/**
 * 反馈
 *
 * @author Cinwell
 *        
 */
class FeedbackController extends BaseController {
	
	public function save() {
		$arr = I('post.');
		$model = M('feedback');
		$arr['UserId'] = cookie('_uid');
		$arr['CreateTime'] = time();
		$r = $model->data($arr)->add();
		if ($r) {
			$this->success('反馈成功');
		}else{
			$this->error('反馈失败，请稍后再试');
		}
	}

}