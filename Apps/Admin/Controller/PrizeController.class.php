<?php

namespace Admin\Controller;

use Home\Model\prize_configModel;
use Home\Model\view_prize_record_listModel;
use Home\Model\prize_recordModel;

/**
 * 抽奖管理
 *
 * @author NENER
 *        
 */
class PrizeController extends BaseController {
	public function index() {
		$nick = I ( 'nick' );
		$code=I('code');
		$s = I ( 's' );
		if (! $s) {
			$wa = array (
					'Status' => array (
							'gt',
							- 2 
					) 
			);
		} elseif (( int ) $s != - 1 && ( int ) $s != 10) {
			$this->error ( '不要瞎搞', U ( '/' ) );
			return false;
		} else {
			$wa = array (
					'Status' => ( int ) $s 
			);
		}
		
		if ($nick) {
			$wa ['Nick'] = array (
					'like',
					'%' . $nick . '%' 
			);
		}
		if($code){
			$wa ['Code']=array('like','%' . $code . '%' );
		}
		$m = new view_prize_record_listModel ();
		$r = $m->getlist ( $wa, 10 );
		$this->assign ( 'list', $r ['list'] );
		$this->assign ( 'page', $r ['page'] );
		$this->assign ( 'nick', $nick);
		$this->assign ( 'prizecode', $code);
		$this->display ();
	}
	/**
	 * 兑奖操作
	 * @param unknown $Id  */
	public function saveprize($Id) {
		$m = new prize_recordModel ();
		$r = $m->where ( array (
				'Id' => (int)$Id 
		) )->save ( array (
				'Status' => - 1,
				'UpdateTime' => time () 
		) );
		if (! $r) {
			$this->error ( '兑奖失败' );
		} else {
			$m=new view_prize_record_listModel();
			$r=$m->where(array('Id'=>$Id))->find();
			CSYSN($r['UserId'], '兑奖成功', '你已成功兑换 &nbsp;<span class="text-orange">'.$r['PraiseName'].'</span>&nbsp;奖品：<span class="text-orange">'.$r['PraiseContent'].'</span>');
			$this->success ( '兑奖成功' );
		}
	}
	/**
	 * 抽奖配置  */
	public function setting() {
		$m = new prize_configModel ();
		$list = $m->getprize ( array (
				'Status' => 10 
		), 1, true );
		$this->assign ( 'list', $list );
		$this->display ();
	}
	/**
	 * 保存抽奖配置
	 * @return boolean  */
	public function saveset() {
		if (! IS_POST) {
			$this->error ( '不要瞎搞' );
			return false;
		}
		$arr = I ( 'post.' );
		$m = new prize_configModel ();
		if ($m->saveone ( $arr )) {
			$this->success ( '保存成功' );
		} else {
			$this->error ( '保存失败' );
		}
	}
	/**
	 * 异步获取数据
	 *
	 * @param int $Id        	
	 */
	public function getset($Id) {
		$m = new prize_configModel ();
		$r = $m->getprize ( array (
				'Id' => $Id 
		), 2 );
		if (! $r) {
			$msg ['status'] = 0;
			$msg ['info'] = '获取数据失败';
		} else {
			$msg ['status'] = 1;
		}
		$msg ['data'] = $r;
		$msg ['method'] = 'update';
		echo json_encode ( $msg );
	}
	/**
	 * 异步删除
	 *
	 * @param int $Id        	
	 */
	public function delset($Id) {
		$m = new prize_configModel ();
		if ($m->where ( array (
				'Id' => $Id 
		) )->save ( array (
				'Status' => - 1 
		) )) {
			$this->success ( '删除成功' );
		} else {
			$this->error ( '删除失败' );
		}
	}
}
?>