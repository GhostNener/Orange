<?php

namespace Admin\Controller;

/**
 * 后台首页
 *
 * @author Cinwell
 *        
 */
class IndexController extends BaseController {
	public function index() {
		$userModel = M('user');
		$goodsModel = M('goods');
		$logModel = M('logs');
		$giftModel = M('gift_order');

		$userList = $userModel->where('FROM_UNIXTIME(RegistTime, "%Y%m%d") ='. date("Ymd"))->order('RegistTime desc')->field('Nick,RegistTime')->limit(5)->select();
		$goodsList = $goodsModel->where('FROM_UNIXTIME(CreateTime, "%Y%m%d") ='. date("Ymd"))->order('CreateTime desc')->field('Title,CreateTime')->limit(5)->select();
		$logList = $logModel->where('FROM_UNIXTIME(Time, "%Y%m%d") ='. date("Ymd") .' AND Type="pay"')->order('Time desc')->field('UserName,Action,Time')->limit(5)->select();

		$userCount = $userModel->where('FROM_UNIXTIME(RegistTime, "%Y%m%d") ='. date("Ymd"))->count();
		$goodsCount = $goodsModel->where('FROM_UNIXTIME(CreateTime, "%Y%m%d") ='. date("Ymd"))->count();
		$logCount = $logModel->where('FROM_UNIXTIME(Time, "%Y%m%d") ='. date("Ymd") .' AND Type="pay"')->field('SUM(Action) as pay')->select();
		$giftCount = $giftModel -> where('Status = 10') ->count();
		$complainCount = $goodsModel->where('Status = -1')->count();

		$this->assign ( 'userList', $userList );
		$this->assign ( 'goodsList', $goodsList );
		$this->assign ( 'logList', $logList );

		$this->assign('userCount',$userCount);
		$this->assign('goodsCount',$goodsCount);
		$this->assign('logCount',$logCount);
		$this->assign('giftCount',$giftCount);
		$this->assign('complainCount',$complainCount);

		$this->display ();
	}
}