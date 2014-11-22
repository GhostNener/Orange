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
	 * @param userid 
	 * @return $allCount 关注数
	 * @author LongG
	 */
	public function getAttention($userid){
		$allCount = $this->where ( array(
				'UserId' => $userid 
		) )->count ();
		return $allCount;
	}
	
	/**
	 * 获取粉丝数
	 * @param $userid
	 * @return 个数
	 * @author LongG
	 */
	public function getFans($userid){
		$fans = $this -> where(array(
				'AttentionId' => $userid
		)) -> count ();
		return $fans;
	}
	
	/**
	 * 删除关注
	 * @param $AttentionId 被关注人Id, $userid 关注人Id
	 * @return  array status ,msg
	 * @author LongG
	 */
	public function del($AttentionId, $userid){
		$data = array (
				'UserId' => $userid,
				'AttentionId' => $AttentionId,
		);
		/*判断是否已关注*/
		$msg = $this->checkIsAtten($AttentionId, $userid);
		if (!$msg['status']) {
			return $msg;
		}
		$rst = $this->where ( $data )->delete ();
		if ($rst) {
			return array (
					'status' => 1,
					'msg' => "取消关注完成" 
			);
		} else {
			return array (
					'status' => 0,
					'msg' => "取消关注失败" 
			);
		}
	}
	
	/**
	 * 添加关注
	 * @param $AttentionId 被关注人Id, $userid 关注人Id
	 * @return  array status ,msg
	 * @author LongG
	 */
	public function add($AttentionId, $userid){
		$data = array (
				'AttentionId' => $AttentionId,
				'UserId' => $userid,
				'CreateTime' => time ()
		);
		/*判断是否已关注*/
		$msg = $this->checkIsAtten($AttentionId, $userid);
		if ($msg['status']) {
			return $msg;
		}
		$atten = M('attention');
		$rst = $atten ->data($data)-> add();
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
	 * @param $$AttentionId 被关注人Id, $userid 关注人Id
	 * @return  array status ,msg
	 * @author LongG
	 */
	public function checkIsAtten($AttentionId, $userid){
		$rst = $this -> where(array (
				'UserId' => $userid,
				'AttentionId' => $AttentionId 
		)) -> find();
		if (!$rst) {
			return array (
					'status' => 0,
					'msg' => "未关注" 
			);
		} else {
			return array (
					'status' => 1,
					'msg' => "你已经关注过了" 
			);
		}
	}
}