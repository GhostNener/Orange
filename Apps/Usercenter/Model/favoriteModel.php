<?php
namespace Usercenter\Model;

use Think\Model;

/**
 * 心愿单模型
 * 
 * @author DongZ
 *
 */
class favoriteModel extends Model{
	
	/**
	 * 添加
	 */
	public function addfavorite($Id,$userid){
		$data = array(
			'UserId' => $userid,
			'GoodsId' => $Id,
			'CreateTime' => date ( "Y-m-d H:i:s", time () ),
			'Status' => 10
		);
		$rst = $this->add($data);
		if ($rst){
			return array(
				'status' => 1,
				'msg' => "添加成功"
			);
		}else {
			return array(
				'status' => 0,
				'msg' => "添加失败"
			);
		}
	}
	
	/**
	 * 删除
	 */
	public function del($Id){
		$whereArr = array(
			'Id' => $id
		);
		$rst = $this->where($whereArr) -> delete();
		if ($rst){
			return array(
				'status' => 1,
				'msg' => "删除成功"
			);
		}else {
			return array(
				'status' => 0,
				'msg' => "删除失败"
			);
		}
	}
}