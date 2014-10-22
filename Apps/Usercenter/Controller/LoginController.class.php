<?php

namespace Usercenter\Controller;

use Think\Controller;
import('ORG.Util.Image');
/**
 * 登录控制器
 * 
 * @author NENER
 *        
 */
class LoginController extends Controller {

	public function index() {
		$this->display('Index/login');
	}	
}
?>