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
	
	/**
	 * 冻结商品
	 *
	 * @param int $id        	
	 * @param number $type
	 *        	1:购买购买冻结，2：举报被冻结
	 */
	public function freeze($id, $type = 1) {
		switch ($type) {
			case 1 :
				$st = 20;
				break;
			case 2 :
				$st = 30;
				break;
			default :
				return false;
		}
		return ($this->where ( array (
				'Id' => $id,
				'Status' => 10 
		) )->save ( array (
				'Status' => $st 
		) ));
	}
	
	/**
	 * 查找一个商品
	 *
	 * @param int $id        	
	 * @return obj
	 * @author NENER
	 */
	public function findone($id) {
		$model = $this->where ( array (
				'Status' => 10,
				'Id' => $id 
		) )->find ();
		return $model;
	}
	/**
	 * 商品点击 评论 收藏 操作 量处理
	 *
	 * @param int $gid
	 *        	商品Id
	 * @param int $type
	 *        	1:浏览，2：收藏，3：评论
	 *        @param $isInc  是否是增加
	 * @author NENER
	 */
	public function VCChhandle($gid, $type = 1,$isInc=true) {
		if (! $this->where ( array (
				'Status' => 10,
				'Id' => $gid 
		) )->find ()) {
			return false;
		}
		$filed = '';
		switch ($type) {
			case 1 :
				$filed = 'Views';
				break;
			case 2 :
				$filed = 'Collection';
				break;
			case 3 :
				$filed = 'CommentCount';
				break;
			default :
				return false;
		}
		if($isInc){
			return ($this->where ( array (
				'Status' => 10,
				'Id' => $gid 
			) )->setInc ( $filed ));
		}else{
			return ($this->where ( array (
				'Status' => 10,
				'Id' => $gid 
			) )->setDec ( $filed ));			
		}

	}
	
	/**
	 * 计算发布费用
	 *
	 * @param array $arr
	 *        	Price,Server数组
	 * @param number $type
	 *        	1：返回数组 status，msg，cost 2：直接返回花费
	 * @return number multitype:number
	 * @author NENER
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
		/* 计算服务费 */
		$scost = $se->computecost ( $arr ['Server'] );
		/* 计算发布费 */
		$temp = ceil ( $arr ['Price'] * 0.04 );
		if($temp>C('MAX_PUBLISH_COST')){
			$temp=C('MAX_PUBLISH_COST');
		}
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
	 * @param array $arr
	 *        	：Price，Server
	 * @param int $uid
	 *        	：uid
	 * @author NENER
	 */
	private function payserver($arr, $uid) {
		$um = new userModel ();
		$b = $um->getbalance ( $uid, 2 );
		$c = $this->computecost ( $arr, 2 );
		if (! $c || ! $b || $b < $c) {
			return false;
		}
		return ($um->payEM ( $uid, $c ));
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
		if ($uid == - 1 || ! $uid) {
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
		$postarr ['TradeWayTxt'] = gettradewaytxt ( ( int ) $postarr ['TradeWay'] );
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
			/* 建立商品索引 */
			$searchdata = array (
					'GoodsId' => $goodsid,
					'SearchTitle' => $postarr ['Title'] . ' ' . $postarr ['Presentation'] 
			);
			$smodel = new goods_searchModel ();
			$smsg = $smodel->saveone ( $searchdata );
			handleEXP ( $uid,3 );
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
	
	/**
	 * 商品下架
	 * @param $goodsId, $userid
	 */
	public function del($goodsId , $userid) {
		$rst = $this->where ( 
				array (
				'UserId' => $userid,
				'Id' => $goodsId,
		) )->save ( array ( 'Status' => 40 ) ) ;
		if ($rst) {
			return array (
					'status' => 1,
					'msg' => "下架成功" 
			);
		} else {
			return array (
					'status' => 0,
					'msg' => "下架失败" 
			);
		}
	}
}

?>