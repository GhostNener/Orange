<?php
namespace Usercenter\Model;

use Think\Model;

/**
 * 关注模型
 * @author DongZ
 *
 */
class attentionModel extends Model{
	/**
	 * 查询关注数
	 * @param array $wherearr  
	 * @return $allCount 关注数
	 * @author LONGG
	 */
	public function getAttention($wherearr){
		$allCount = $this->where ( $wherearr )->count ();
		return $allCount;
	}
	
	/**
	 * 获取粉丝数
	 */
	public function getFans($userid){
		$whereArr = array('AttentionId' => $userid);
		$fans = $this -> where($whereArr) -> select();
		return count($fans);
	}
	
	public function delattention($whereall){
		$model = $this -> where($whereall) -> delete();
		return $model;
	}
}