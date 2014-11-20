<?php

namespace Usercenter\Model;

use Think\Model;

/**
 * 订单模型
 * @author LONGG
 *
 */

class view_goods_order_listModel extends Model{
	/**
	 * 查询订单及商品
	 *
	 * @param array $wherearr , $limit   
	 * @return array page 翻页组装,list 列表
	 * @author LONGG
	 *        
	 */
	public function getorder($wherearr = array('Status'=>10), $limit = 6) {
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