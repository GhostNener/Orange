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
	 * 查询关注
	 */
	public function getAttention($userid){
		$whereArr = array('UserId' => $userid);
		$attention = $this ->table('attention a,user u')-> where(array($whereArr,'a.AttentionId=u.Id'))->field('a.*,u.Nick as AttentionNick') -> select();
		if ($attention){
			$msgarr['attention'] = $attention;
			$msgarr['attnumber'] = count($attention);
			$msgarr['status'] = 1;
		}else {
			$msgarr['status'] = 0;
			$msgarr['msg'] = "查询失败";
		}
		return $msgarr;
	}
	
	/**
	 * 获取粉丝数
	 */
	public function getFans($userid){
		$whereArr = array('AttentionId' => $userid);
		$fans = $this -> where($whereArr) -> select();
		return count($fans);
	}
}