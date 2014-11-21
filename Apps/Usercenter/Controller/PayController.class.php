<?php

namespace Usercenter\Controller;

require_once 'ORG/Alipay/alipay_submit.class.php';
/**
 * 充值
 *
 * @author Cinwell
 *        
 */
class PayController extends LoginController {
	public function index() {
		$r = M ( 'settings' )->where ( array (
				'key' => 'enabled' 
		) )->find ();
		if (! $r || ( int ) $r ['value'] == 0) {
			$this->error ( '充值服务暂停', U ( '/' ) );
			die ();
		}
		$this->display ();
	}
	/**
	 * 填写金额页面
	 */
	public function paytp() {
		$this->display ();
	}
	
	/**
	 * 支付宝双接口(数据处理)
	 */
	public function alipay() {
		if (! IS_POST) {
			$this->error ( '不要瞎搞' );
			return;
		}
		$config = C ( 'ALIPAY' );
		$submit = $config ['SUBMIT'];
		$parm = $config ['PARAM'];
		$parm ['out_trade_no'] = 'E' . date ( 'YmdHis' ) . randstr ( 5 ); // 生成订单号
		$parm ['price'] =0.01 ;//( int ) I ( 'money' );
		if ($parm ['price'] <= 0) {
			$this->error ( '不要瞎搞' );
			return;
		}
		// 建立请求
		$alipaySubmit = new \AlipaySubmit ( $submit );
		$html_text = $alipaySubmit->buildRequestForm ( $parm, "post", "跳转中" );
		echo $html_text;
	}
	
	public function qrcode() {
		$this->display ();
	}
	public function check() {
		$code = I ( 'code' );
		$verify = new \Think\Verify ();
		$verify->reset = true;
		$rst = $verify->check ( $code, '' );
		if ($rst) {
			$this->success ( '验证码正确' );
		} else {
			$this->error ( '验证码错误' );
		}
	}
	// 检验订单号
	public function tradeno() {
		if (! IS_POST) {
			$this->error ( '不要瞎搞' );
			return;
		}
		$model = M ( 'alipay' );
		$tradeno = I ( 'tradeno' );
		$result = $model->where ( array (
				'Enabled' => 0,
				'TradeTailNo' => $tradeno 
		) )->find ();
		
		if ($result) {
			
			$result ['Enabled'] = 1;
			$r = $model->save ( $result );
			if ($r) {
				
				// 给用户充值
				$model = M ( 'user' );
				$user = $model->where ( array (
						'Id' => cookie ( '_uid' ) 
				) )->find ();
				$user ['E-Money'] += $result ['Amount'];
				$r = $model->save ( $user );
				if ($r) {
					CSYSN ( cookie ( '_uid' ), '充值成功', "共充值" . $result ['Amount'] . "元，请到个人中心核对" );
					// 日志
					logs ( $result ['Amount'], 4 );
					$this->success ( "充值成功，共充值" . $result ['Amount'] . "元，请到个人中心核对", U ( '/Usercenter/Index/index' ) );
				}
			}
		}
		
		$this->error ( '充值失败，请确认订单号是否填写正确' );
	}
}
