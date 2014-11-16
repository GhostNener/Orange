<?php

namespace Admin\Controller;

/**
 * 后台首页
 *
 * @author Cinwell
 *        
 */
class PayController extends BaseController {
	public function index() {
		//https://consumeprod.alipay.com/record/advanced.htm?beginDate=2014.08.16&beginTime=00%3A00&endDate=2014.11.16&endTime=24%3A00&dateRange=threeMonths&status=all&keyword=bizOutNo&keyValue=&dateType=createDate&minAmount=&maxAmount=&fundFlow=in&tradeModes=FP&tradeType=tranAlipay&categoryId=&_input_charset=utf-8
		$this->display ();
	}

	public function setting() {
		$this->display();
	}
}