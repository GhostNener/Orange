<?php

namespace Usercenter\Model;

use Think\Model;
/**
 * 关注列表模型
 * LONGG
 */

class view_user_attention_listModel extends Model {
	/**
	 * 获取关注列表
	 *
	 * @param array $wherearr , $limit   
	 * @return array page 翻页组装,list 列表
	 * @author LONGG
	 *        
	 */
	public function getattention($wherearr = array('Status'=>10), $limit = 6, $baseurl = ACTION_NAME, $defaultpar = true) {
		$allCount = $this->where ( $wherearr )->count ();
		$Page = new \Think\Page ( $allCount, $limit, null, $defaultpar );
		$showPage = $Page->show ( $baseurl );
		$list = $this->where ( $wherearr )->limit ( $Page->firstRow . ',' . $Page->listRows )->order ( 'CreateTime DESC ' )->select ();
		return array (
				'status' => 1,
				'page' => $showPage,
				'list' => $list 
		);
	}
}