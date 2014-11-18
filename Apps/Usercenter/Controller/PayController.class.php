<?php

namespace Usercenter\Controller;

/**
 * 充值
 *
 * @author Cinwell
 *        
 */
class PayController extends LoginController {
	public function index() {
		$this->display();
	}

	public function qrcode() {
		$this->display();
	}

	public function check() {
		$code = I('code');

		$verify = new \Think\Verify ();
		$verify->reset = true;
		$rst = $verify->check ( $code, '' );
		if($rst){
			$this->success('验证码正确');
		}else{
			$this->error('验证码错误');
		}
	}

	public function tradeno(){
		
		$this->success("充值成功，共充值50元，请到个人中心核对", U('/Usercenter/Index/index'));
	}
}
