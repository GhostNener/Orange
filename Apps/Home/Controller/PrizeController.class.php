<?php

namespace Home\Controller;

use Home\Model\prize_configModel;
/**
 * 抽奖控制器
 *
 * @author NENER
 *        
 */
class PrizeController extends LoginController {
	public function _initialize() {
		parent::_initialize ();
		if (! isactivated ()) {
			$this->error ( '帐号未激活', U ( '/u/act' ), 1 );
			die ();
		}
	}
	public function index() {
		$r=checkprize(cookie('_uid'));
		$c=$r?0:300;
		$this->assign('pcount',$c);
		$m=new prize_configModel();
		$list=$m->getprize(array('Status'=>10),1,true);

		$this->assign('plist',$list);
		$this->display ();
	}
	public function getprize() {
		if(!IS_POST){
			$this->error('不要瞎搞',U('/'));
			return false;
		}		
		$m=new prize_configModel();
		$r=$m->prize(cookie('_uid'));
		echo json_encode($r);
	}
}
?>