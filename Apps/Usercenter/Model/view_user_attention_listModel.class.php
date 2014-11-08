<?php

namespace Usercenter\Model;

use Think\Model;
/**
 * 关注列表模型
 */

class view_user_attention_listModel extends Model {
	/**
	 * 查询所有的关注
	 */
	public function getattention($whereall){
		$rst = $this -> where($whereall) -> select();
		return $rst;
	}
}