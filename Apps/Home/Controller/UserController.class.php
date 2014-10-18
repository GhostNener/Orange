<?php

namespace Home\Controller;

use Think\Controller;

class UserController extends Controller {
	public function index() {
		$this->display ( 'Index/index' );
	}
	public function addaddress() {
		$this->assign('modif','add')->display ( 'modifaddress' );
	}
	public function saveaddress() {
		$userid=0;
		$arr=I('post.');
		if(!IS_POST||!$arr){
			$this->error('页面不存在');
		}
		$modifarr=array('add','update');
		if(!in_array(strtolower($arr['modif']), $modifarr)){
			$this->error('非法操作');
		}
		$data=array(
		'UserId'=>$userid,
		'Tel'=>$arr['Tel'],
		'QQ'=>$arr['QQ'],
		'Address'=>$arr['Address'],
		'IsDefault'=>$arr['IsDefault'],
		'Status'=>10
		);
		if(M('user_address')->data($data)->add()){
			$this->success('操作成功',U('index'),10000);
		}else {
			$this->error('操作失败');}
	}
}