<?php

namespace Admin\Controller;

require_once 'ORG/AES/AES.php';
/**
 * 支付宝订单
 *
 * @author Cinwell
 *        
 */
class PayController extends BaseController {

	public function index() {

		$model = M('alipay');
		$list = $model->select();

		// 总数
		$allCount = $model->count ();
		// 分页
		$Page = new \Think\Page ( $allCount, 20 );
		
		$showPage = $Page->show ();
		// 分页查询
		$list = $model->limit ( $Page->firstRow . ',' . $Page->listRows )->select ();

		$this->assign ( 'list', $list );
		$this->assign ( 'page', $showPage );

		$this->display ();
	}

	public function setting() {
		$model = M('settings');
		$cookies = $model->where('`key` = "cookies"')->find();
		$cookies = $cookies['value'];

		//凯撒+base64解密
		$cookies = base64_decode($cookies);
		$this->assign('cookies',$cookies);
		$this->display();
	}

	public function save() {
		$cookies = I('post.cookies');
		if (!$cookies) {
			$this->error('操作错误');
		}
		$cookies=base64_encode($cookies);
		$model = M('settings');
		$data['value'] = $cookies;
/* 		$this->error($cookies); */
		$result = $model->where('`key` = "cookies"')->save($data);
		if ($result) {
			$this->success('保存成功');
		}
		$this->error('未更新内容');
	}
}