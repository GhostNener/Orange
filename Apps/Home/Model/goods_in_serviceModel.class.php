<?php

namespace Home\Model;

use Think\Model;

/**
 * 商品对应的服务
 *
 * @author NENER
 *        
 */
class goods_in_serviceModel extends Model {
	
	/**
	 * 用户模型自动完成
	 *
	 * @var unknown
	 */
	protected $_auto = array (
			array (
					'Status',
					10,
					self::MODEL_INSERT 
			) 
	);
	/**
	 *
	 * @param unknown $data        	
	 * @return boolean
	 */
	public function saveone($data) {
		if (! $data) {
			return false;
		}
		$arrid = explode ( '|', $data ['Server'] );
		$this->where ( array (
				'GoodsId' => $data ['GoodsId'] 
		) )->delete ();
		$temp = false;
		$dal = M ();
		$dal->startTrans ();
		foreach ( $arrid as $k => $v ) {
			$mo = $this->create ( array (
					'GoodsId' => $data ['GoodsId'],
					'ServiceId' => $v 
			) );
			if ($this->add ($mo )) {
				$temp = true;
			} else {
				$temp = false;
			}
		}
		if ($temp) {
			$dal->commit ();
		} else {
			$dal->rollback ();
		}
		return $temp;
	}
}

?>