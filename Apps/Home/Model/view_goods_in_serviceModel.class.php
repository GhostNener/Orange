<?php

namespace Home\Model;

use Think\Model;

/**
 * 商品信息以及对应的服务表
 *
 * @author NENER
 *        
 */
class view_goods_in_serviceModel extends Model {
	
	/**
	 * 获得商品列表
	 *
	 * @param unknown $wherearr        	
	 * @param number $limit        	
	 * @return array:status，page，list
	 */
	public function getlist($wherearr = array('Status'=>10), $limit = 6) {
		$allCount = $this->where ( $wherearr )->count ();
		$Page = new \Think\Page ( $allCount, $limit );
		$showPage = $Page->show ();
		$list = $this->where ( $wherearr )->limit ( $Page->firstRow . ',' . $Page->listRows )->select ();
		return array (
				'status' => 1,
				'page' => $showPage,
				'list' => $list 
		);
	}
	/**
	 * 获取某个商品的服务
	 * 
	 * @param unknown $goodsid
	 *        	商品Id
	 * @return array:status，msg
	 *
	 */
	public function getservice($goodsid) {
		$rst = $this->field ( 'ServiceName', 'ServiceId' )->where ( array (
				'Id' => $goodsid 
		) )->select ();
		return array (
				'status' => 1,
				'msg' => $rst 
		);
	}
}