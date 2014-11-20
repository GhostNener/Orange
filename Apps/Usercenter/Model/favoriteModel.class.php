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
	public function addone($goodsId, $userid = null) {
		if (! $userid) {
			$userid = cookie ( '_uid' );
		}
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
			$today = $this->where ( array (
					'UserId' => $userid,
					'Status' => 10,
					'CreateTime' => array (
							'egt',
							strtotime ( date ( 'Y-m-d' ) ) 
					) 
			) )->count ();
			$EXP = ( int ) C ( 'COMMENT_EXP_FOR_DAY' );
			if ($today <= $EXP) {
				handleEXP ( $userid );
			}
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
	 * 
	 * @param $goodsId, $userid        	
	 */
	public function del($goodsId, $userid) {
		$data = array (
				'UserId' => $userid,
				'GoodsId' => $goodsId 
		);
		$rst = $this->where ( $data )->delete ();
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