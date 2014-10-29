<?php

namespace Home\Model;

use Think\Model;

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
			) 
	);
	/**
	 * 用户模型自动完成
	 *
	 * @var unknown
	 */
	protected $_auto = array (
			array (
					'Createtime',
					NOW_TIME,
					self::MODEL_INSERT 
			) 
	);
	/**
	 * 保存商品
	 *
	 * @author NENER
	 * @param array $postarr:
	 *        	post数组
	 * @return array 保存信息： 包含 status 状态 ；
	 *         msg 消息
	 */
	public function savegoods($postarr) {
		if (! $postarr) {
			return array (
					'status' => 0,
					'msg' => '没有数据' 
			);
		}
		$goodsid = $postarr ['GoodsId'];
		if (! $goodsid || ! is_numeric ( $goodsid ) || ( int ) $goodsid <= 0) {
			return array (
					'status' => 0,
					'msg' => '操作失败' 
			);
		}
		$postarr ['Status'] = 10;
		$dal = M ();
		$dal->startTrans ();
		// 保存商品订单
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
				'Server',
				'TradeWay',
				'Status',
				'Createtime' 
		) )->where ( array (
				'Id' => $goodsid 
		) )->save ( $goodsmodel );
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
		if ($rst1 && $rst2) {
			$dal->commit ();
			$searchdata = array (
					'GoodsId' => $goodsid,
					'SearchTitle' => $postarr ['Title'].' '.$postarr ['Presentation'] 
			);
			$smodel = new goods_searchModel ();
			$smsg = $smodel->addone ( $searchdata );
			return array (
					'status' => 1,
					'msg' => '操作成功' 
			);
		} else {
			$dal->rollback ();
			return array (
					'status' => 0,
					'msg' => '操作失败' 
			);
		}
	}
}

?>