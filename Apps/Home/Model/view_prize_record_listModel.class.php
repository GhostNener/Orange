<?php

namespace Home\Model;

use Think\Model;
use Think\Crypt\Driver\Think;

/**
 * 商品图片模型
 *
 * @author NENER
 *        
 */
class view_prize_record_listModel extends Model {
	/**
	 * 获取获奖记录
	 * @param unknown $wa
	 * @param number $limit
	 * @return multitype:unknown  */
	public function getlist($wa = array('Status'=>array('gt',-2)), $limit = 10) {
		$count = $this->where ( $wa )->order ( 'CreateTime DESC' )->count ();
		$Page = new \Think\Page ( $count, $limit );
		$showpage = $Page->show ();
		$list = $this->where ( $wa )->limit ( $Page->firstRow . ',' . $Page->listRows )->order ( 'CreateTime DESC' )->select ();
		return array (
				'list' => $list,
				'page' => $showpage 
		);
	}
}