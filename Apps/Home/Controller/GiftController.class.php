<?php

namespace Home\Controller;

use Home\Model\goods_categoryModel;
use Home\Model\activityModel;
use Usercenter\Model\user_addressModel;

/**
 * 礼品页面初始化
 *
 * @author Cinwell
 *        
 */
class GiftController extends BaseController {
	public function index() {
		$model = M ( 'Gift' );
		// 查询条件
		$map['Status'] = array('eq',10);
		$map['Amount'] = array('gt',0);
		// 总数
		$allCount = $model->where ( $map )->count ();
		// 分页
		$Page = new \Think\Page ( $allCount, 18 );
		
		$showPage = $Page->show ();
		// 分页查询
		$list = $model->where ( $map )->order ( 'CreateTime desc' )->limit ( $Page->firstRow . ',' . $Page->listRows )->select ();
		
		$this->assign ( 'list', $list );
		$this->assign ( 'page', $showPage );
		
		$this->assign ( 'empty', '<h1 class="text-center text-import">暂无礼品</h1>' );
		$this->display ();
	}

	public function exchange() {
		$giftid = I ( 'giftid' );
		$amount = I('Amount');

		$model = M('gift');
		$result = $model->where(array('Id'=>$giftid))->find();
		$giftName = $result['Name'];

		//判断数量够不够
		if($result['Amount'] - $amount >= 0){
			
			$result['Amount'] = $result['Amount'] - $amount;
			$model->where(array('Id'=>$giftid))->save($result);

		}else{
			$this->error('抱歉，商品已被兑换完了');
		}

		//判断钱够不够
		$price = $result['Price'];
		$price = $price * $amount;
		$model = M('user');
		$result = $model->where(array('Id'=>cookie('_uid')))->find();
		if ($result['E-Money'] < $price) {
			$this->error('金橘余额不足，请充值后再购买');
		}else{
			$result['E-Money'] = $result['E-Money'] - $price;
			$model -> where(array('Id'=>cookie('_uid')))->save($result);
		}

		//生成订单保存
		$model = M('gift_order');
		$data['Amount'] = (int) $amount;
		$data['giftId'] = (int) $giftid;
		$data['UserId'] = (int) cookie('_uid');
		$data['AddressId'] = (int) I('AddressId');
		$data['CreateTime'] = time();
		$data['Status'] = 10;
		$result = $model->data($data)->add();
		if (!$result) {
			$this->error('操作失败，请重试');
		}

		//发送通知
		CSYSN(cookie('_uid'),'礼品兑换成功','你兑换的 ' . $giftName . ' 礼品已确认成功，工作人员会在一个工作日内与你联系，请保持电话畅通。');
		$this->success ( '兑换成功,请到消息中心查看详情');
	}

	//得到用户地址
	public function getinfo() {
		$m = new user_addressModel ();
		$alist = $m->getall ( cookie ( '_uid' ) );
		echo json_encode ( array (
						'status' => 1,
						'data' => $alist 
		) );
	}
}