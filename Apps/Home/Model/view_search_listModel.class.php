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
	 * 通过关键字获取商品列表
	 *
	 * @param array $wherearr        	
	 * @return array page 翻页组装,list 列表
	 * @author NENER
	 *        
	 */
	private function getlist($key, $limit = 6) {
		$allcount = $this->query ( "SELECT COUNT(*) AS COUNT FROM view_search_list WHERE `Status`=10 AND MATCH(SearchTitle) AGAINST('" . $key . "' IN BOOLEAN MODE);" );
		$allcount = $allcount [0] ['COUNT'];
		$Page = new \Think\Page ( $allcount, $limit );
		$showPage = $Page->show ();
		$q = "SELECT * FROM view_search_list WHERE `Status`=10 AND MATCH(SearchTitle) AGAINST('" . $key . "' IN BOOLEAN MODE) ORDER BY CreateTime DESC";
		$qp = $q . " LIMIT " . $Page->firstRow . "," . $Page->listRows;
		$list = $this->query ( $qp );
		return array (
				'page' => $showPage,
				'list' => $list 
		);
	}
	/**
	 * 搜索商品
	 *
	 * @param string $key
	 *        	标题
	 * @param number $limit
	 *        	每页个数
	 * @return array page 翻页组装,list 列表
	 * @author NENER
	 */
	public function getsearchlist($key, $limit = 6) {
		$arr = searchpart ( $key );
		$arrtemp = $arr;
		$key = implode ( ' +', $arr );
		$keyt = '+*' . implode ( '* +*', $arrtemp ) . '*';
		$key = '+' . $key;
		$arr = $this->getlist ( $key, $limit );
		/* 搜不到进行通配符搜索 */
		if (count ( $arr ['list'] ) <= 0) {
			$arr = $this->getlist ( $keyt, $limit );
		}
		return $arr;
	}
}