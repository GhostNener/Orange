<?php

namespace Usercenter\Model;

use Think\Model;
use Home\Model\goodsModel;

/**
 * 心愿单模型
 *
 * @author DongZ
 *        
 */
class favoriteModel extends Model {
	
	/**
	 * 添加心愿单
	 */
	public function addfavorite($goodsId, $userid) {
		$data = array (
				'UserId' => $userid,
				'GoodsId' => $goodsId,
				'CreateTime' => time (),
				'Status' => 10 
		);
		$check = $this->where ( array (
				'UserId' => $userid,
				'GoodsId' => $goodsId 
		) )->find ();
		if ($check) {
			return array (
					'status' => 0,
					'msg' => '你已经添加过了' 
			);
		}
		$like = $this->create ( $data );
		if (! $like) {
			return array (
					'status' => 0,
					'msg' => $this->getError () 
			);
		}
		$rst = $this->add ( $like );
		if ($rst) {
			$m = new goodsModel ();
			$m->VCChhandle ( $goodsId, 2 );
			return array (
					'status' => 1,
					'msg' => "添加成功" 
			);
		} else {
			return array (
					'status' => 0,
					'msg' => "添加失败" 
			);
		}
	}
	
	/**
	 * 删除
	 */
	public function del($Id) {
		$whereArr = array (
				'Id' => $Id 
		);
		$rst = $this->where ( $whereArr )->delete ();
		if ($rst) {
			return array (
					'status' => 1,
					'msg' => "删除成功" 
			);
		} else {
			return array (
					'status' => 0,
					'msg' => "删除失败" 
			);
		}
	}
}