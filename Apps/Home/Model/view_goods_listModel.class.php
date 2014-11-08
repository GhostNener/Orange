<?php

namespace Home\Model;

use Think\Model;

/**
 * 商品列表模型
 *
 * @author NENER
 *        
 */
class view_goods_listModel extends Model {
	/**
	 * 获取商品列表
	 *
	 * @param array $wherearr        	
	 * @return array page 翻页组装,list 列表
	 * @author NENER
	 *        
	 */
	public function getlist($wherearr = array('Status'=>10), $limit = 6) {
		$allCount = $this->where ( $wherearr )->count ();
		$Page = new \Think\Page ( $allCount, $limit );
		$showPage = $Page->show ();
		$list = $this->where ( $wherearr )->limit ( $Page->firstRow . ',' . $Page->listRows )->order ( 'CreateTime DESC ' )->select ();
		return array (
				'status' => 1,
				'page' => $showPage,
				'list' => $list 
		);
	}
	/**
	 * 获取一串随机商品
	 *
	 * @param number $nubmer
	 *        	随机数量
	 * @return array:status,list
	 * @author NENER
	 */
	public function getrandlist($nubmer = 6) {
		$wherearr = array (
				'Status' => 10 
		);
		$allCount = $this->where ( $wherearr )->count ();
		if ($allCount > $nubmer) {
			$beg = mt_rand ( 1, ($allCount - $nubmer) );
			$list = $this->where ( $wherearr )->limit ( $beg, $nubmer )->select ();
		} else {
			$list = $this->where ( $wherearr )->limit ( 1, $nubmer )->select ();
		}
		return array (
				'status' => 1,
				'list' => $list 
		);
	}
	
	/**
	 * 获取单个商品详情
	 *
	 * @param
	 *        	int Id
	 * @return goods 商品信息,commentlist 评论列表,goodsimg商品图片
	 *        
	 *        
	 */
	public function getgoodsdetails($Id) {
		$whereArr = array (
				'Id' => $Id,
				'Status' => 10 
		);
		$goods = M ( "view_goods_list" )->where ( $whereArr )->find ();
		$whereArr1 = array (
				'GoodsId' => $Id,
				'Status' => 10 
		);
		$whereArr3 = array (
				'GoodsId' => $Id 
		);
		$commentlist = M ( "view_goods_comment_list" )->where ( $whereArr1 )->limit ( C ( 'COMMENTS_LIST_COUNT' ) )->select ();
		$goodsimg = M ( 'goods_img' )->where ( $whereArr3 )->select ();
		return array (
				'goods' => $goods,
				'commentlist' => $commentlist,
				'goodsimg' => $goodsimg 
		);
	}
}