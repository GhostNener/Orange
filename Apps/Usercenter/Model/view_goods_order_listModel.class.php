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
	 * 查询完成订单及商品
	 *
	 * @param $userid 用户Id
	 * @param $limit 分页显示个数
	 * @param $type  1.完成交易的购买 2.完成交易的出售 3.未完成交易的购买
	 * @return array page 翻页组装,list 列表
	 * @author LongG
	 *        
	 */
	public function getorder($userid, $limit = 6, $type = 1) {
		if (!$userid) {
			$userid = cookie('_uid');
		}
		switch ($type) {
			case 1 :	//完成交易的购买订单
				$where = array (
						'BuyerId' => $userid,
						'Status' => 25
				);
				break;
			case 2 :	//完成交易的出售订单
				$where = array (
						'SellerId' => $userid,
						'Status' => 25
				);
				break;
			case 3 :	//未完成交易的购买订单
				$arr['SellerId'] = cookie('_uid');
				$arr['BuyerId'] = cookie('_uid');
				$arr['_logic'] = 'OR'; 
				$where['_complex'] = $arr; 
				$where['Status']  = array('neq',25); 
				break;
		}
		$allCount = $this->where ( $where )->count ();
		$Page = new \Think\Page ( $allCount, $limit);
		$showPage = $Page->show ();
		$list = $this->where ( $where )->limit ( $Page->firstRow . ',' . $Page->listRows )->order ( 'CreateTime DESC ' )->select ();
		return array (
				'page' => $showPage,
				'list' => $list 
		);
	}
}