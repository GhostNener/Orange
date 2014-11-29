<?php

namespace Admin\Controller;

use Home\Model\prize_configModel;

/**
 *
 * @author NENER
 *        
 */
class PrizeController extends BaseController {
	public function index() {
		$s=I('s');
		if(!$s){
			$wa=array('Status'=>array('gt',-2));
		}elseif ((int)$s!=-1&&(int)$s!=10){
			$this->error('不要瞎搞',U('/'));
			return false;
		}
		$wa=array('Status'=>(int)$s);
		/*待完成  */
		$this->display ();
	}
	public function setting() {
		$m = new prize_configModel ();
		$list = $m->getprize ( array (
				'Status' => 10 
		), 1, true );
		$this->assign ( 'list', $list );
		$this->display ();
	}
	
	public function saveset(){
		if(!IS_POST){
			$this->error('不要瞎搞');
			return false;
		}
		$arr=I('post.');
		$m=new prize_configModel();
		if($m->saveone($arr)){
			$this->success('保存成功');
		}else{
			$this->error('保存失败');
		}
	}
	/**
	 * 异步获取数据
	 * @param unknown $Id  */
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
		$msg['method']='update';
		echo json_encode ( $msg );
	}
	/**
	 * 异步删除
	 * @param unknown $Id  */
	public function delset($Id){
		$m = new prize_configModel ();
		if($m->where(array('Id'=>$Id))->save(array('Status'=>-1))){
			$this->success('删除成功');
		}else{
			$this->error('删除失败');
		}
	}
}
?>