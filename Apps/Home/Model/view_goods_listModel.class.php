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
	public function getlist($wherearr = array('Status'=>10), $limit = 6, $baseurl = ACTION_NAME, $defaultpar = true) {
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
	 * @param int $type
	 *        	1:返回所有信息，2：返回基础信息
	 * @return goods 商品信息,commentlist 评论列表,goodsimg商品图片
	 *        
	 *        
	 */
	public function getgoodsdetails($Id, $type = 1, $limit = null) {
		if ($type == 2) {
			return $this->findone ( $Id );
		}
		if (! $limit) {
			$limit = C ( 'COMMENTS_LIST_COUNT' );
		}
		$goods = $this->where ( array (
				'Id' => $Id,
				'Status' => 10 
		) )->find ();
		$m = new view_goods_comment_listModel ();
		$commentlist = $m->getlist ( $Id, $limit );
		$goodsimg = M ( 'goods_img' )->where ( array (
				'GoodsId' => $Id 
		) )->select ();
		return array (
				'goods' => $goods,
				'commentlist' => $commentlist ['list'],
				'goodsimg' => $goodsimg 
		);
	}
	/**
	 * 获得单个商品信息（不包含评论）
	 *
	 * @param number $id        	
	 * @return obj
	 */
	private function findone($id) {
		$r = $this->where ( array (
				'Id' => ( int ) $id 
		) )->find ();
		return $r;
	}
}