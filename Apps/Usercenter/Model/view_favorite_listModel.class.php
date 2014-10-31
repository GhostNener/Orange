<?php
namespace Usercenter\Model;

use Think\Model;

/**
 * 心愿单模型
 * @author DongZ
 *
 */
class view_favorite_listModel extends Model{
	
	/**
	 * 获取心愿单列表
	 * @param array $wherearr        	
	 * @return array page 翻页组装,list 列表
	 *        
	 */
	public function getlist($userid) {
		$wherearr = array(
			'Status' => 10,
			'UserId' => $userid
		); 
		$limit = 10;
		$allCount = $this->where ( $wherearr )->count ();
		$Page = new \Think\Page ( $allCount, $limit );
		$showPage = $Page->show ();
		$list = $this->where ( $wherearr )->select ();
		return array (
				'page' => $showPage,
				'list' => $list 
		);
	}
}