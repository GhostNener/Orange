<?php

namespace Usercenter\Controller;

use Think\Controller;

/**
 * 公共控制器
 *
 * @author NENER
 *        
 */
class PublicController extends Controller {
	/**
	 * 生成验证码
	 *
	 * @author NENER
	 */
	Public function verifycode() {
		$verify = new \Think\Verify ();
		$verify->codeSet = '0123456789';
		$verify->length = 4;
		$verify->entry ();
	}
	/**
	 * 验证验证码 用于JS等
	 *
	 * @author NENER
	 * @param
	 *        	$code
	 * @param string $id        	
	 */
	function check_verify($code, $id = '') {
		$verify = new \Think\Verify ();
		$verify->reset = false;
		$rst = $verify->check ( $code, $id );
		echo $rst;
	}
	
	/**
	 * 处理支付宝订单:异步请求（支付宝双接口）
	 */
	public function handlealipay() {
		$arr = I ( 'post.' );
		if (! $arr ) {
			$this->error ( '不要瞎搞' );
		}
		$r = M ( 'alipay_order' )->where ( array (
				'TradeCode' => strtoupper ( trim ( $arr ['out_trade_no'] ) ) 
		) )->save ( array (
				'TradeNo' => trim($arr['trade_no']),
				'Status' => 0,
				'UpdateTime' => time () ,
				'BuyerId'=>$arr['buyer_id'],
				'BuyEmail'=>$arr['buyer_email']
		) );
		$this->success ( $r );
	}
}
?>