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
	
	/**
	 * 删除关注
	 * @param $goodsId, $userid
	 * @return  1 or 0
	 * @author LONGG
	 */
	public function del($AttentionId, $userid){
		$data = array (
				'UserId' => $userid,
				'AttentionId' => $AttentionId,
		);
		$rst = $this->where ( $data )->delete ();
		if ($rst) {
			return array (
					'status' => 1,
					'msg' => "删除成功" 
			);
		} else {
			return array (
					'status' => 0,
					'msg' => "删除失败" 
			);
		}
	}
	
	/**
	 * 关注
	 * @param array $wherearr  
	 * @return  1 or 0
	 * @author LONGG
	 */
	public function add($whereall){
		$atten = M('attention');
		$rst = $atten ->data($whereall)-> add();
		if ($rst) {
			return array (
					'status' => 1,
					'msg' => "关注成功" 
			);
		} else {
			return array (
					'status' => 0,
					'msg' => "关注失败" 
			);
		}
	}
	
	/**
	 * 此人是否已关注
	 * @param array $where
	 * @return  是否关注
	 * @author LONGG
	 */
	public function checkIsAtten($where){
		$rst = $this -> where($where) -> find();
		if ($rst) {
			return array (
					'status' => 1,
					'msg' => "已关注" 
			);
		} else {
			return array (
					'status' => 0,
					'msg' => "未关注" 
			);
		}
	}
}