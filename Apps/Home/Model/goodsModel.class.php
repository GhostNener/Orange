<?php

namespace Home\Model;

use Think\Model;
use Usercenter\Model\userModel;

/**
 * 商品模型
 *
 * @author NENER
 *        
 */
class goodsModel extends Model {
	
	/**
	 * 自动验证
	 *
	 * @var unknown
	 */
	protected $_validate = array (
			array (
					'imgcount',
					array (
							1,
							20 
					),
					'至少有一张图片',
					self::EXISTS_VALIDATE,
					'between' 
			),
			array (
					'TradeWay',
					array (
							1,
							3 
					),
					'交易方式有误',
					self::EXISTS_VALIDATE,
					'between' 
			) 
	);
	/**
	 * 用户模型自动完成
	 *
	 * @var unknown
	 */
	protected $_auto = array (
			array (
					'CreateTime',
					NOW_TIME,
					self::MODEL_INSERT 
			) 
	);
	private function gettradetxt($wayid) {
		if (! $wayid) {
			return '';
		}
		switch ($wayid) {
			case 1 :
				return '线上';
				break;
			case 2 :
				return '线下';
				break;
			case 3 :
				return '线上/线下';
				break;
			default :
				return '';
				break;
		}
	}
	
	/**
	 * 计算发布费用
	 *
	 * @param array $arr
	 *        	Price，Server
	 * @return multitype:number string
	 */
	public function computecost($arr, $type = 1) {
		if (! $arr || ! $arr ['Price']) {
			if ($type == 2) {
				return 0;
			}
			return array (
					'status' => 0,
					'msg' => '空数据',
					'cost' => 0 
			);
		}
		$se = new goods_serviceModel ();
		/*计算服务费  */
		$scost = $se->computecost ( $arr ['Server'] );
		/*计算发布费  */
		$temp = ceil($arr ['Price'] * 0.03 );
		if ($type == 2) {
			return ($scost + $temp);
		}
		return array (
				'status' => 1,
				'msg' => '获取成功',
				'cost' => ($scost + $temp) 
		);
	}
	/**
	 * 支付发布费用
	 *
	 * @param unknown $arr
	 *        	：Price，Server
	 * @param unknown $uid
	 *        	：uid
	 */
	private function payserver($arr, $uid) {
		$um = new userModel ();
		$b = $um->getbalance ( $uid, 2 );
		$c = $this->computecost ( $arr, 2 );
		if (! $c || ! $b || $b < $c) {
			return false;
		}
		if (M ( 'user' )->where ( array (
				'Id' => $uid 
		) )->save ( array (
				'E-Money' => ($b - $c) 
		) )) {
			return true;
		} else {
			return false;
		}
	}
	/**
	 * 保存商品
	 *
	 * @author NENER
	 * @param array $postarr:
	 *        	post数组
	 * @return array 保存信息： 包含 status 状态 ；
	 *         msg 消息
	 */
	public function savegoods($postarr, $uid = -1) {
		if (! $postarr) {
			return array (
					'status' => 0,
					'msg' => '没有数据' 
			);
		}
		if ($uid == - 1) {
			$uid = cookie ( '_uid' );
		}
		$goodsid = $postarr ['GoodsId'];
		if (! $goodsid || ! is_numeric ( $goodsid ) || ( int ) $goodsid <= 0) {
			return array (
					'status' => 0,
					'msg' => '操作失败' 
			);
		}
		/* 服务数据 */
		$sdat ['GoodsId'] = $goodsid;
		$sdat ['Server'] = $postarr ['Server'];
		/* 计算费用 */
		$cost ['Server'] = explode ( '|', $sdat ['Server'] );
		$cost ['Price'] = $postarr ['Price'];
		unset ( $postarr ['Server'] );
		$postarr ['Status'] = 10;
		$dal = M ();
		$dal->startTrans ();
		// 保存商品订单
		/* 支付服务费 */
		$rp = $this->payserver ( $cost, $uid );
		if (! $rp) {
			$dal->rollback ();
			return array (
					'status' => 0,
					'msg' => '余额不足！' 
			);
		}
		$postarr ['TradeWayTxt'] = $this->gettradetxt ( ( int ) $postarr ['TradeWay'] );
		$goodsmodel = $this->create ( $postarr );
		if (! $goodsmodel) {
			return array (
					'status' => 0,
					'msg' => $this->getError () 
			);
		}
		$rst1 = $this->field ( array (
				'Title',
				'Price',
				'CostPrice',
				'Presentation',
				'CategoryId',
				'AddressId',
				'TradeWayTxt',
				'TradeWay',
				'Status',
				'CreateTime' 
		) )->where ( array (
				'Id' => $goodsid 
		) )->save ( $goodsmodel );
		$smodel = new goods_in_serviceModel ();
		$srst = $smodel->saveone ( $sdat );
		// 修改该商品图片状态
		$rst2 = D ( 'goods_img' )->where ( array (
				'GoodsId' => $goodsid 
		) )->save ( array (
				'Status' => 10,
				'Title' => $postarr ['Title'] 
		) );
		if ($rst2) {
			// 修改新添加的关键字为审核状态
			$rst3 = D ( 'goods_category_keyword' )->where ( array (
					'Keyword' => strtolower ( $postarr ['Title'] ) 
			) )->save ( array (
					'Status' => 1,
					'CategoryId' => $postarr ['CategoryId'] 
			) );
		}
		if ($rst1 && $rst2 && $srst) {
			$dal->commit ();
			$searchdata = array (
					'GoodsId' => $goodsid,
					'SearchTitle' => $postarr ['Title'] . ' ' . $postarr ['Presentation'] 
			);
			$smodel = new goods_searchModel ();
			$smsg = $smodel->saveone ( $searchdata );
			return array (
					'status' => 1,
					'msg' => '操作成功' 
			);
		} else {
			$dal->rollback ();
			return array (
					'status' => 0,
					'msg' => '操作成功' 
			);
		}
	}
}

?>