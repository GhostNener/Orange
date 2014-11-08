<?php

namespace Home\Model;

use Think\Model;

/**
 * 商品服务模型
 *
 * @author NENER
 *        
 */
class goods_serviceModel extends Model {
	
	/**
	 * 获取服务
	 *
	 * @author NENER
	 * @return array ：所有服务
	 */
	public function getall() {
		$arr = $this->where ( array (
				'Status' => 10 
		) )->select ();
		return $arr;
	}
	/**
	 * 计算服务费用
	 *
	 * @param array $arr
	 *        	服务id数组
	 * @return number 花费
	 * @author NENER
	 */
	public function computecost($arr) {
		if (! $arr) {
			return 0;
		}
		$cost = 0;
		foreach ( $arr as $k => $v ) {
			$r = $this->field ( 'Price' )->where ( array (
					'Id' => $v,
					'Status' => 10 
			) )->find ();
			if (! $r) {
				continue;
			} else {
				$cost = $cost + $r ['Price'];
			}
		}
		return $cost;
	}
}

?>