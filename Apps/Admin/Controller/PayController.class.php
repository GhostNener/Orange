<?php

namespace Admin\Controller;

/**
 * 支付宝订单
 *
 * @author Cinwell
 *        
 */
class PayController extends BaseController {

	public function index() {

		$model = M('alipay');

		// 总数
		$allCount = $model->count ();
		// 分页
		$Page = new \Think\Page ( $allCount, 10 );
		
		$showPage = $Page->show ();
		// 分页查询
		$list = $model->order('CreateTime desc')->limit ( $Page->firstRow . ',' . $Page->listRows )->select ();

		$this->assign ( 'list', $list );
		$this->assign ( 'page', $showPage );

		$this->display ();
	}

	public function setting() {
		$model = M('settings');
		//$cookies = $model->where('`key` = "cookies"')->find();
		//$cookies = $cookies['value'];

		$regex = $model->where('`key` = "regex"')->find();
		$regex = $regex['value'];

		//base64解密
		//$cookies = base64_decode($cookies);

		//$this->assign('cookies',$cookies);
		$this->assign('regex',$regex);
		$this->display();
	}

	public function save() {

		$model = M('settings');

		//判断密码是否正确
		$password = i('post.password');
		$password = md5($password);
		$result = $model->where( array(
				'key' => 'password',
				'value' => $password
				) )->find();
		if(!$result){
			$this->error('密码错误，请滚粗');
		}
		
		$cookies = I('post.cookies');
		if ($cookies) {
			$cookies=base64_encode($cookies);
			$data1['value'] = $cookies;
			$r1 = $model->where('`key` = "cookies"')->save($data1);
		}

		C('DEFAULT_FILTER','htmlspecialchars');
		$regex = I('post.regex');
		C('DEFAULT_FILTER','htmlspecialchars,strip_tags');

		$regex = htmlspecialchars_decode($regex);

		if (!$regex) {
			$this->error('操作错误');
		}

		$data2['value'] = $regex;
		$r2 = $model->where('`key` = "regex"')->save($data2);
		if ($r1 || $r2) {
			$this->success('保存成功');
		}
		$this->error('未更新内容');
	}
}