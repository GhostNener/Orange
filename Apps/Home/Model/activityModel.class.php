<?php

namespace Home\Model;

use Think\Model;

/**
 * 首页活动
 *
 * @author NENER
 *        
 */
class activityModel extends Model {
	/**
	 * 获取活动
	 * 
	 * @param array $wherearr        	
	 * @param number $limit   
	 * @author NENER     	
	 */
	public function getlist($wherearr=array('Status'=>10), $limit = 5) {
		$arr=$this->where($wherearr)->limit($limit)->order('CreateTime desc')->select();
		return $arr;
	}
}