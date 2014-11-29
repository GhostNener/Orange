<?php

namespace Home\Model;

use Think\Model;

class prize_recordModel extends Model {
	
	/**
	 * 自动验证
	 *
	 * @var unknown
	 */
	protected $_validate = array (
			array (
					'UserId',
					'',
					'你已经抽过奖了！',
					self::EXISTS_VALIDATE,
					'unique' 
			) 
	);
	/**
	 * 添加抽奖记录
	 *
	 * @param unknown $uid        	
	 * @param unknown $pid        	
	 * @param unknown $code        	
	 * @return multitype:number string
	 */
	public function addone($uid, $pid, $code) {
		$data = array (
				'Code' => $code,
				'UserId' => $uid,
				'PrizeId' => $pid,
				'CreateTime' => time (),
				'Status' => 10 
		);
		$c = $this->create ( $data );
		if (! $c) {
			return array (
					'status' => 0,
					'msg' => $this->getError () 
			);
		}
		$dal = M ();
		$dal->startTrans ();
		$r1 = $this->add ( $c );
		$m = new prize_configModel ();
		$r2 = $m->where ( array (
				'Id' => (int)$pid 
		) )->setDec ( 'PraiseCount', 1 );
		if (! $r1||$r2 ) {
			$dal->rollback ();
			return array (
					'status' => 0,
					'msg' => '抽奖失败' 
			);
		}
		$dal->commit ();
		return array (
				'status' => 1,
				'msg' => '抽奖成功' 
		);
	}
}

?>