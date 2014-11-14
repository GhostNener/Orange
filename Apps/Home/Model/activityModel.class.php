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
	public function getlist( $wherearr=array('Status'=>10),$limit = 5,$type=1) {
		if($type==2){
			
		}
		$arr=$this->where($wherearr)->limit($limit)->order('CreateTime desc')->select();
		return $arr;
	}

	/**
	 * 获取活动(分页)
	 * 
	 * @param number $limit   
	 * @param array $wherearr        	
	 * @author Cinwell     	
	 */
	public function getpagelist($wherearr=array('Status'=>10),$limit = 5) {
		// 总数
		$allCount = $this->where ( $wherearr )->count ();
		// 分页
		$Page = new \Think\Page ( $allCount, $limit );
		
		$showPage = $Page->show ();
		$list = $this->where ( $wherearr )->limit ( $Page->firstRow . ',' . $Page->listRows )->order ( 'CreateTime DESC ' )->select ();
		return array (
				'page' => $showPage,
				'list' => $list
		);
	}

	public function getdetail($value)
	{
		$result = $this->where(array('Id'=>$value))->find();
		return $result;
	}
}