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
		$parm ['out_trade_no'] = strtoupper ( $parm ['out_trade_no'] );
		$parm ['price'] = ( int ) I ( 'money' );
		if ($parm ['price'] <= 0) {
			$this->error ( '不要瞎搞' );
			return;
		}
		if (! M ( 'alipay_order' )->add ( array (
				'TradeCode' => $parm ['out_trade_no'],
				'Money' => $parm ['price'],
				'Status' => 0,
				'CreateTime' => time (),
				'UpdateTime' => time () 
		) )) {
			$this->error ( '建立账单失败', U ( '/' ) );
			return;
		}
		// 建立请求
		$alipaySubmit = new \AlipaySubmit ( $submit );
		$html_text = $alipaySubmit->buildRequestForm ( $parm, "post", "记得选择 即时到帐交易 ！！！！！！！！！！" );
		echo $html_text;
	}
	/**
	 * 处理支付宝订单:成功处理（支付宝双接口）
	 */
	public function handlealipay() {
		$arr = I ( 'get.' );
		if (! $arr || ! $arr ['out_trade_no'] || ! $arr ['trade_no']) {
			$this->error ( '不要瞎搞', U ( '/' ) );
		}
		$dal = M ();
		$dal->startTrans ();
		$r1 = M ( 'alipay_order' )->where ( array (
				'Status' => 0,
				'TradeNo' => trim ( $arr ['trade_no'] ),
				'TradeCode' => strtoupper ( trim ( $arr ['out_trade_no'] ) ) 
		) )->save ( array (
				'Status' => 1,
				'UpdateTime' => time () 
		) );
		if (! $r1) {
			$dal->rollback ();
			$this->error ( '不要瞎搞', U ( '/' ) );
		}
		$or=M ( 'alipay_order' )->where ( array (
				'TradeNo' => trim ( $arr ['trade_no'] ),
				'TradeCode' => strtoupper ( trim ( $arr ['out_trade_no'] ) ) 
		) )->find();
		$arr ['price']=$or['Money'];
		$model = M ( 'user' );
		$user = $model->where ( array (
				'Id' => cookie ( '_uid' ) 
		) )->find ();
		$user ['E-Money'] += $arr ['price'];
		$r = $model->save ( $user );
		if (! $r) {
			$dal->rollback ();
			$this->error ( '充值失败，请联系检查是否支付成功'.$arr ['price'], U ( '/' ) );
			return;
		} else {
			$dal->commit ();
			CSYSN ( cookie ( '_uid' ), '充值成功', "共充值" . $arr ['price'] . "元，请到个人中心核对" );
			// 日志
			logs ( $arr ['price'], 4 );
			
			M ( 'alipay' )->where ( array (
					'TradeNo' => trim ( $arr ['trade_no'] ) 
			) )->save ( array (
					'Enabled' => 1 
			) );
			$this->success ( "充值成功，共充值" . $arr ['price'] . "元，请到个人中心核对", U ( '/u' ) );
		}
	}
	public function qrcode() {
		$r = M ( 'settings' )->where ( array (
				'key' => 'enabled'
		) )->find ();
		if (! $r || ( int ) $r ['value'] == 0) {
			$this->error ( '钱包转账服务暂停', U ( '/' ) );
			die ();
		}
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
			if (M ( 'alipay_order' )->where ( array (
					'Status' => 1,
					'TradeNo' => $result ['TradeNo'] 
			) )->find ()) {
				$result ['Enabled'] = 1;
				$r = $model->save ( $result );
				$this->error ( '你已经充值过了', U ( '/u' ) );
				return;
			}
			$result ['Enabled'] = 1;
			$dal = M ();
			$dal->startTrans ();
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
					$dal->commit ();
					CSYSN ( cookie ( '_uid' ), '充值成功', "共充值" . $result ['Amount'] . "元，请到个人中心核对" );
					// 日志
					logs ( $result ['Amount'], 4 );
					$this->success ( "充值成功，共充值" . $result ['Amount'] . "元，请到个人中心核对", U ( '/u' ) );
				} else {
					$dal->rollback ();
				}
			}
		}
		$this->error ( '充值失败，请确认订单号是否填写正确' );
	}
}
