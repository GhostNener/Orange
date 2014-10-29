<?php

namespace Home\Model;

use Think\Model;

/**
 * 商品全文检索
 *
 * @author NENER
 *        
 */
class view_search_listModel extends Model {
	/**
	 * 获取商品列表
	 *
	 * @param array $wherearr        	
	 * @return array page 翻页组装,list 列表
	 *        
	 */
	public function getlist($key, $limit = 10) {
		$allcount = $this->query ( "SELECT COUNT(*) AS COUNT FROM view_search_list WHERE MATCH(SearchTitle) AGAINST('" . $key . "');" );
		$allcount = $allcount [0] ['COUNT'];
		$Page = new \Think\Page ( $allcount, $limit );
		$showPage = $Page->show ();
		$q = "SELECT * FROM view_search_list WHERE MATCH(SearchTitle) AGAINST('" . $key . "')";
		$qp = $q . " LIMIT " . $Page->firstRow . "," . $Page->listRows;
		$list = $this->query ( $qp );
		return array (
				'page' => $showPage,
				'list' => $list 
		);
	}
}