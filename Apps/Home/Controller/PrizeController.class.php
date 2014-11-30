<?php

namespace Home\Controller;

use Home\Model\prize_configModel;
use Home\Model\view_prize_record_listModel;
use Home\Model\prize_recordModel;

/**
 * 抽奖控制器
 *
 * @author NENER
 *        
 */
class PrizeController extends LoginController {
	public function _initialize() {
		parent::_initialize ();
		if (! isactivated ()) {
			$this->error ( '帐号未激活', U ( '/u/act' ), 1 );
			die ();
		}
	}
	public function index() {
		$r = checkprize ( cookie ( '_uid' ) );
		$c = $r ? 0 : 300;
		$this->assign ( 'pcount', $c );
		$m = new prize_configModel ();
		$list = $m->getprize ( array (
				'Status' => 10 
		), 1, true );
		
		$this->assign ( 'plist', $list );
		$this->display ();
	}
	/**
	 * 抽奖
	 *
	 * @return boolean
	 */
	public function getprize() {
		if (! IS_POST) {
			$this->error ( '不要瞎搞', U ( '/' ) );
			return false;
		}
		$m = new prize_configModel ();
		$r = $m->prize ( cookie ( '_uid' ) );
		echo json_encode ( $r );
	}
	/**
	 * 代金券兑换
	 */
	public function recharge() {
		$this->display ();
	}
	/**
	 * 兑换代金券
	 *
	 * @return boolean
	 */
	public function postrecharge() {
		$code = I ( 'code' );
		if (! IS_POST | ! $code) {
			$this->error ( '不要瞎搞', U ( '/' ) );
			return false;
		}
		$m = new view_prize_record_listModel ();
		$r = $m->where ( array (
				'Code' => $code,
				'Status' => 10,
				'UserId' => cookie ( '_uid' ) 
		) )->find ();
		if (! $r) {
			$this->error ( '兑换码不存在或已经使用过', U ( '/' ) );
			return false;
		}
		if (( int ) $r ['PraiseFeild'] == 1) {
			$count = 100;
		} elseif (( int ) $r ['PraiseFeild'] == 8) {
			$count = 0.1;
		} else {
			$this->error ( '此兑换码不是代金券兑换码', U ( '/' ) );
			return false;
		}
		$m = new prize_recordModel ();
		$r = $m->recharge ( ( int ) $r ['Id'], ( int ) cookie ( '_uid' ), $count );
		if (! $r) {
			$this->error ( '兑换失败' );
		} else {
			$this->success ( '兑换成功，请到个人中心核对！', U ( '/u' ) );
		}
	}
}
?>