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
	 * @param number $limit   
	 * @param array $wherearr        	
	 * @author NENER     	
	 */
	public function getlist($limit = 5, $wherearr=array('Status'=>10)) {
		$arr=$this->where($wherearr)->limit($limit)->order('CreateTime desc')->select();
		return $arr;
	}

	/**
	 * 获取活动分页
	 * 
	 * @param number $limit   
	 * @param array $wherearr        	
	 * @author Cinwell     	
	 */
	public function getpage($limit = 5,$wherearr=array('Status'=>10)) {
		// 总数
		$allCount = $this->where ( $wherearr )->count ();
		// 分页
		$Page = new \Think\Page ( $allCount, $limit );
		
		$showPage = $Page->show ();

		return $showPage;
	}

	public function getdetail($value)
	{
		$result = $this->where(array('Id'=>$value))->find();
		return $result;
	}
}