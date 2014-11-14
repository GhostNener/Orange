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
		$model = M('gift');
		$result = $model->where(array('Id'=>$giftid))->find();
		if($result['Amount'] > 0){
			$result['Amount']--;
			$model->where(array('Id'=>$giftid))->save($result);

			
			
		}else{
			var_dump($result);
			$this->error('抱歉，商品已被兑换完了');
		}
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