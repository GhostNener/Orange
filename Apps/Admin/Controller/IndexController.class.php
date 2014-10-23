<?php

namespace Admin\Controller;

use Think\Controller;
use Usercenter\Model\userModel;
/**
 * 后台首页
 *
 * @author NENER
 *        
 */
class IndexController extends BaseController {
	public function index() {
		$this->display ();
	}
}