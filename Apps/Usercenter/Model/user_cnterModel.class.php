<?php

namespace Usercenter\Model;

use Think\Model;

/**
 * 用户中心模型
 *
 */

class user_cnterModel extends Model{
	/**
	 * 修改用户信息
	 */
	public function updateUser($userid){
		$user = M('user');
		$res = $user->where(array(
			'Id'=>$userid,
			'Status'=>10
		))->save();
		if ($res) {
			return true;
		}
		return false;
	}

	/**
	 * 查询用户信息
	 */
	public function selectUser($userid){
		$user = M('user');
		$model = $user->where(array(
			'Id'=>$userid,
			'Status'=>10
		))->find();
		return $model;
	}

	/**
	 * 查询所有被自己购买的商品
	 */
	public function selectAllBuy($userid){
		$order = M('goods_order');
		$model = $order -> where(array(
				'BuyerId' => $userid,
		 		'Status' => 20
		))->select();
		return $model;
	}

	/**
	 * 查询所有自己已出售的商品
	 */
	public function selectAllSell($userid){
		$order = M('goods_order');
		$model = $order -> where(array(
				'SellerId'=>$userid,
				'Status'=>20
		))->select();
		return $model;
	}

	/**
	 * 查询所有自己正在出售的商品
	 */
	public function selectAllGoods($userid){
		$goods = M('goods');
		$model = $goods -> where(array(
				'UserId'=>$userid,
				'Status'=>10
		))->select();
		return $model;
	}

	//	/**
	//	 * 查询关注
	//	 */
	//	public function allGoods($userid){
	//		$goods = M('goods');
	//		$model = $goods -> where(array(
	//				'UserId'=>$userid,
	//				'Status'=>10
	//		))->select();
	//		return $model;
	//	}
	//
	//	/**
	//	 * 查询心愿单
	//	 */
	//	public function allGoods($userid){
	//		$goods = M('goods');
	//		$model = $goods -> where(array(
	//				'UserId'=>$userid,
	//				'Status'=>10
	//		))->select();
	//		return $model;
	//	}
}