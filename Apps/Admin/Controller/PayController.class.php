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
		//https://consumeprod.alipay.com/record/advanced.htm?beginDate=2014.08.16&beginTime=00%3A00&endDate=2014.11.16&endTime=24%3A00&dateRange=threeMonths&status=all&keyword=bizOutNo&keyValue=&dateType=createDate&minAmount=&maxAmount=&fundFlow=in&tradeModes=FP&tradeType=tranAlipay&categoryId=&_input_charset=utf-8

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

		//base64+凯撒加密
		$cookies = base64_encode($cookies);

		$model = M('settings');
		$data['value'] = $cookies;
		$result = $model->where('`key` = "cookies"')->save($data);
		if ($result) {
			$this->success('保存成功');
		}
		$this->error('未更新内容');
	}
}