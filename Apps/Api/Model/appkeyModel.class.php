<?php

namespace Api\Model;

use Think\Model;

/**
 * appkey 模型
 *
 * @author NENER
 *        
 */
class appkeyModel extends Model {
	/**
	 * 检查appkey
	 *
	 * @param unknown $key        	
	 * @return boolean
	 */
	public function checkkey($key) {
		if (! $key) {
			return false;
		}
		if (session ( '?APPKEY' )) {
			if (session ( 'APPKEY' ) == $key) {
				return true;
			} else {
				return false;
			}
		}
		$model = $this->where ( array (
				'Key' => $key,
				'Status' => 10 
		) )->find ();
		if (! $model) {
			return false;
		}
		session ( 'APPKEY', $key );
		return true;
	}
}

?>