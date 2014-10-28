<?php
namespace Home\Model;
use Think\Model;

/**
 * 订单模型
 * @author DongZ
 *
 */
class goods_orderModel extends Model{
	
	/**
	 * 填写订单
	 */
	public function fillorder($Id){
		$userid = cookie ( '_uid' );
		$model = M('goods_fillorder');
		$whereArr = array(
			'Id' => $Id
		);
		$goods_fillorder = $model -> where($whereArr) -> select();
		// 查询用户地址
		$whereArr1 = array(
			'Status' => 10,
			'UserId' => $userid 
		);
		$useraddrlist = D ( 'user_address' )->order ( 'IsDefault DESC' )->where($whereArr1)->select ();
		return array(
			'goods_fillorder'  => $goods_fillorder,
			'useraddrlist' => $useraddrlist
		);
	}
	
	/**
	 * 生成订单
	 * 
	 * @param array $postarr:
	 *        	post数组
	 * @return array 保存信息： 包含 status 状态 ；
	 *         msg 消息
	 */
	public function order($postarr){
		if (empty ( $postarr )) {
			return array (
					'status' => 0,
					'msg' => '没有数据' 
			);
		}
		$goods_order = M ( "goods_order" );
		$data = array (
				'BuyerId' => cookie ( '_uid' ),
				'BuyerAddId' => $_POST ['BuyerAddId'],
				'SellerId' => $_POST ['SellerId'],
				'SellerAddId' => $_POST ['SellerAddId'],
				'GoodsId' => $_POST ['GoodsId'],
				'Price' => $_POST ['Price'],
				'E-Money' => $_POST ['E-Money'],
				'CreateTime' => date ( "Y-m-d H:i:s", time () ),
				//'AssesseId' => $_POST['AssesseId'],
				'Status' => 10 
		);
		$z = $goods_order->add ( $data );
		if ($z) {
			$goods = M ( "goods" );
			$where = array (
					'Id' => $_POST ['GoodsId'] 
			);
			$goods->where ( $where )->setField ( 'Status', 20 );
			return array(
				'status' => 1,
				'msg' => '操作成功'
			);
			// $this->redirect('Goods/showgoods',array('Id'=>$data['GoodsId']),0,'');
		} else {
			return array(
				'status' => 0,
				'msg' => '操作失败'
			);
		}
	}
}