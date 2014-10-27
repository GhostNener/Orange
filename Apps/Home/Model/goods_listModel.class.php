<?php

namespace Home\Model;

use Think\Model;

/**
 * 商品列表模型
 *
 * @author NENER
 *        
 */
class goods_listModel extends Model {
	/**
	 * 获取商品列表
	 *
	 * @param array $wherearr        	
	 * @return array page 翻页组装,list 列表
	 *        
	 */
	public function getlist($wherearr = array('Status'=>10), $limit = 10) {
		$allCount = $this->where ( $wherearr )->count ();
		$Page = new \Think\Page ( $allCount, $limit );
		$showPage = $Page->show ();
		$list = $this->where ( $wherearr )->limit ( $Page->firstRow . ',' . $Page->listRows )->select ();
		return array (
				'page' => $showPage,
				'list' => $list 
		);
	}
}