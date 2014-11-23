<?php

namespace Usercenter\Model;

use Think\Model;

/**
 * 查询用户信息
 */
class view_user_info_avatarModel extends Model {
	/**
	 * 获取个人信息
	 * @param $userid
	 * @return array:status,msg
	 */
	public function getinfo($userid=null,$isapi=false) {
		if(!$userid){
			$userid= cookie('_uid');
		}
		if (! $userid) {
			return array (
					'status' => 0,
					'msg' => '没有登录' 
			);
		}
		$model = $this->where ( array (
				'Id' => $userid 
		) )->find ();
		$credit = $model ['Credit'] / ($model ['TradeCount'] * 5) * 100;
		$credit = $credit > 0 ? $credit : 100;
		$m=new userModel();
		$rank=$m->getranking($model['id']);
		$model['Ranking']=$rank;
		$model['CreditPre']=$credit;
		$model['Grade']=getgrade((int)$model['EXP'],1);
		if($isapi){
			$info='ok';
		}else{
			$info=$model;
		}
		return array (
				'status' => 1,
				'msg' => $info ,
				'info'=>$model
		);
	}
}