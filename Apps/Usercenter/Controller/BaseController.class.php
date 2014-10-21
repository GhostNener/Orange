<?php

namespace Usercenter\Controller;

use Think\Controller;

/**
 * 基础控制器
 * 
 * @author NENER
 *        
 */
class BaseController extends Controller {
	/**
	 * 检测登录
	 */
	public function _initialize() {
		echo uniqid()."<br>";
	}
	public function index() {
	}
}
?>