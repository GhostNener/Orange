<?php

namespace Home\Model;

use Think\Model;

/**
 * 商品评论列表
 *
 * @author NENER
 *        
 */
class view_goods_comment_listModel extends Model {
	/**
	 * 获取评论
	 *
	 * @param int $id
	 *        	商品Id
	 * @param number $limit        	
	 * @return status,page,list
	 */
	public function getlist($id, $limit = 10) {
		$wa = array (
				'GoodsId' => ( int ) $id 
		);
		$ac = $this->where ( $wa )->count ();
		$Page = new \Think\Page ( $ac, $limit );
		$showPage = $Page->show ();
		$list = $this->where ( $wa )->limit ( $Page->firstRow . ',' . $Page->listRows )->order ( 'CreateTime DESC ' )->select ();
		return array (
				'status' => 1,
				'page' => $showPage,
				'list' => $list 
		);
	}
}