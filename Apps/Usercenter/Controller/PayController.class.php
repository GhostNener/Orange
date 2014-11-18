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

	//检验订单号
	public function tradeno(){
		$model = M('alipay');
		$tradeno = I('tradeno');
		$result = $model -> where(array(
			'Enabled' => 0,
			'TradeTailNo' => $tradeno
			))->find();
		
		if($result){

			$result['Enabled'] = 1;
			$r = $model->save($result);
			if($r){

				//给用户充值
				$model = M('user');
				$user = $model->where(array('Id'=>cookie('_uid')))->find();
				$user['E-Money'] += $result['Amount'];
				$r = $model->save($user);
				if ($r) {
					$this->success("充值成功，共充值".$result['Amount']."元，请到个人中心核对", U('/Usercenter/Index/index'));
				}

			}

		}

		$this->error('充值失败，请确认订单号是否填写正确');
	}
}