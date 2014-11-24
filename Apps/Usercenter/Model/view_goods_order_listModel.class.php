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
	 * 查询未完成订单
	 *
	 * @param $type  
	 * 			1：获取 列表   2：获取 数量 
	 * @param $limit 分页显示个数
	 * @return array or number
	 * @author LongG
	 *        
	 */
	public function getorder($type = 1, $limit = 6, $baseurl=ACTION_NAME, $defaultpar = true, $param=null) {
		$arr['SellerId'] = cookie('_uid');
		$arr['BuyerId'] = cookie('_uid');
		$arr['_logic'] = 'OR'; 
		$wherearr['_complex'] = $arr; 
		$wherearr['Status']  = array('neq',25);

		if ($type == 2) {
			return ($this->where ( $wherearr )->count ());
		} else {
			$allCount = $this->where ( $wherearr )->count ();
			$Page = new \Think\Page ( $allCount, $limit, $param, $defaultpar );
			$showPage = $Page->show ( $baseurl );
			$list = $this->where ( $wherearr )->limit ( $Page->firstRow . ',' . $Page->listRows )->order ( 'CreateTime DESC ' )->select ();
			return array (
					'status' => 1,
					'page' => $showPage,
					'list' => $list 
			);
		}
	}
	
	/**
	 * 查询购买订单
	 *
	 * @param $type
	 * 			  1：获取 列表  2：获取 数量
	  * @param $limit 分页显示个数
	 * @return array or number
	 * @author LongG
	 *        
	 */
	public function getbuyorder($type = 1, $limit = 6) {
		$wherearr = array (
				'BuyerId' => cookie('_uid'),
				'Status' => 25
		);
		if ($type == 2) {
			return ($this->where ( $wherearr )->count ());
		} else {
			$allCount = $this->where ( $wherearr )->count ();
			$Page = new \Think\Page ( $allCount, $limit);
			$showPage = $Page->show ();
			$list = $this->where ( $wherearr )->limit ( $Page->firstRow . ',' . $Page->listRows )->order ( 'CreateTime DESC ' )->select ();
			return array (
					'status' => 1,
					'page' => $showPage,
					'list' => $list 
			);
		}
	}
	
	/**
	 * 查询出售订单
	 *
	 
	 * @param $type
	 * 			  1：获取 列表  2：获取 数量
	 * @param $limit 分页显示个数
	 * @return array or number
	 * @author LongG
	 *        
	 */
	public function getsellorder($type = 1, $limit = 6) {
		$wherearr = array (
				'SellerId' => cookie('_uid'),
				'Status' => 25
		);
		if ($type == 2) {
			return ($this->where ( $wherearr )->count ());
		} else {
			$allCount = $this->where ( $wherearr )->count ();
			$Page = new \Think\Page ( $allCount, $limit);
			$showPage = $Page->show (  );
			$list = $this->where ( $wherearr )->limit ( $Page->firstRow . ',' . $Page->listRows )->order ( 'CreateTime DESC ' )->select ();
			return array (
					'status' => 1,
					'page' => $showPage,
					'list' => $list 
			);
		}
	}
}