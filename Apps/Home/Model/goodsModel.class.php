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
	 * 保存商品
	 *
	 * @param array $postarr:
	 *        	post数组
	 * @return array 保存信息： 包含 status 状态 ；
	 *         msg 消息
	 */
	public function save($postarr) {
		if (empty ( $postarr )) {
			return array (
					'status' => 0,
					'msg' => '没有数据' 
					);
		}
		if (! $postarr ['imgcount'] || ! is_numeric ( $postarr ['imgcount'] ) || ( int ) $postarr ['imgcount'] <= 0) {
			return array (
					'status' => 0,
					'msg' => '没有上传图片' 
					);
		}
		$goodsid = $postarr ['GoodsId'];
		if (! $goodsid || ! is_numeric ( $goodsid ) || ( int ) $goodsid <= 0) {
			return array (
					'status' => 0,
					'msg' => '操作失败' 
					);
		}
		// 需要提交的商品参数
		$data = array (
				'Title' => $postarr ['Title'],
				'Price' => $postarr ['Price'],
				'CostPrice' => $postarr ['CostPrice'],
				'Presentation' => $postarr ['Presentation'],
				'CategoryId' => $postarr ['CategoryId'],
				'AddressId' => $postarr ['AddressId'],
				'Server' => $postarr ['Server'],
				'TradeWay' => $postarr ['TradeWay'],
				'Status' => 10 
		);
		$dal = M ();
		$dal->startTrans ();
		$goods = M ( 'goods' );
		// 保存商品订单
		$rst1 = $goods->where ( array (
				'Id' => $goodsid 
		) )->save ( $data );
		// 修改该商品图片状态
		$rst2 = M ( 'goods_img' )->where ( array (
				'GoodsId' => $goodsid 
		) )->save ( array (
				'Status' => 10,
				'Title' => $postarr ['Title'] 
		) );
		if ($rst2) {
			// 修改新添加的关键字为审核状态
			$rst3 = M ( 'goods_category_keyword' )->where ( array (
					'Keyword' => strtolower ( $postarr ['Title'] ) 
			) )->save ( array (
					'Status' => 1,
					'CategoryId' => $postarr ['CategoryId'] 
			) );
		}
		if ($rst1 && $rst2) {
			$dal->commit ();
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
	/**
	 * 保存图片 记录
	 *
	 * @param array $postarr
	 *        	:post数组
	 * @param int $userid:用户Id
	 * @return array:status,goodsid,msg
	 */
	public function saveimg($postarr, $userid) {
		if (empty ( $postarr )) {
			return array (
					'status' => 0,
					'goodsid' => 0,
					'msg' => '空数据' 
					);
		}
		/* 商品Id */
		$goodsid = $postarr ['_gid'];
		/* 图像Id */
		$imgid = $postarr ['_imgid'];
		$dal = M ();
		$dal->startTrans (); // 事务
		if (! $goodsid || $goodsid <= 0) {
			$goodsid = M ( 'goods' )->add ( array (
					'UserId' => $userid,
					'Status' => 0 
			) );
		}
		if (! $goodsid) {
			$dal->rollback ();
			$this->delallimg ( 1, $imgid );
			return array (
					'status' => 0,
					'goodsid' => 0,
					'msg' => '保存失败' 
					);
		}
		$imgmodel = M ( 'goods_img' );
		$imgmodel->GoodsId = $goodsid;
		$rst = $imgmodel->where ( array (
				'Id' => $imgid 
		) )->save ();
		if ($rst) {
			$dal->commit ();
			return array (
					'status' => 1,
					'goodsid' => $goodsid,
					'msg' => '操作成功' 
					);
		} else {
			$dal->rollback ();
			$this->delallimg ( 1, $imgid );
			M ( 'goods_img' )->where ( array (
					'Id' => $imgid 
			) )->delete ();
			return array (
					'status' => 0,
					'goodsid' => 0,
					'msg' => '保存失败' 
					);
		}
	}
	/**
	 * 上传商品 图片
	 *
	 * @return array:status，imgid，msg
	 */
	public function uploadimg() {

		// 载入图片上传配置
		$config = C ( 'IMG_UPLOAD_CONFIG' );
		$config ['savePath'] = $config ['savePath'] . C ( 'GOODS_IMG_SOURCE' );
		$upload = new \Think\Upload ( $config ); // 实例化上传类
		$images = $upload->upload ();
		// 判断是否有图
		if (! $images) {
			return array (
					'status' => 0,
					'imgid' => 0,
					'msg' => $upload->getError () 
			);
		}
		// 图片保存名
		$imgname = $images ['Filedata'] ['savename'];
		// 图片保存相对路径
		$imgurl = $config ['rootPath'] . $config ['savePath'] . $imgname;
		$urlarr = $this->getallthumb ( $imgurl, $imgname );
		$data = array (
				'GoodsId' => 0,
				'URL' => substr ( $urlarr [0], 1 ),
				'ThumbURL' => substr ( $urlarr [1], 1 ),
				'SourceURL' => substr ( $imgurl, 1 ),
				'Title' => '',
				'Status' => 0 
		);
		$imgid = M ( 'goods_img' )->add ( $data );
		if ($imgid) {
			return array (
					'status' => 1,
					'imgid' => $imgid,
					'msg' => substr ( $urlarr [1], 1 ) 
			);
		} else {
			return array (
					'status' => 0,
					'imgid' => 0,
					'msg' => $upload->getError () 
			);
		}
	}
	/**
	 * 删除单个商品图片记录：数据库 记录 本地图片
	 *
	 * @param int $imgid
	 * @return array:status，msg
	 */
	public function delimg($imgid) {
		$delmode = M ( 'goods_img' )->where ( array (
				'Id' => ( int ) $imgid 
		) )->find ();
		if (! M ( 'goods_img' )->where ( array (
				'Id' => ( int ) $imgid 
		) )->delete ()) {
			return array (
					'status' => 0,
					'msg' => '删除失败' 
					);
		}
		$this->delallimg ( 2, $delmode );
		return array (
				'status' => 1,
				'msg' => '操作成功' 
				);
	}

	/**
	 * 压缩图片
	 *
	 * @param string $url:
	 *        	原始图片的路径
	 * @param string $imgname:
	 *        	保存的文件名【需要包含文件后缀】
	 * @return array:压缩图路径 第一个参数是 大图 第二个是缩略图
	 */
	private function getallthumb($url, $imgname) {
		$rooturl = C ( 'GOODS_IMG_ROOT' );
		$url_8 = $rooturl . C ( 'GOODS_IMG_800' ) . $imgname;
		$url_1 = $rooturl . C ( 'GOODS_IMG_100' ) . $imgname;
		$imagedal = new \Think\Image ();
		$imagedal->open ( $url );
		$size = $imagedal->size ();
		$arrthumb = C ( 'GOODS_IMG_THUMB' );
		$arrmd = C ( 'GOODS_IMG_MD' );
		$imagedal->thumb ( ( int ) $arrmd [0], ( int ) $arrmd [1] )->save ( $url_8 );
		$imagedal->thumb ( ( int ) $arrthumb [0], ( int ) $arrthumb [1], \Think\Image::IMAGE_THUMB_CENTER )->save ( $url_1 );
		return array (
		$url_8,
		$url_1
		);
	}

	/**
	 * 删除单个商品图片:原图，缩略图，正常用图
	 *
	 * @param int $type
	 *        	:操作类型 ：1表示根据Id，其他表示根据model
	 * @param object $idormodel
	 *        	：商品Id，操作为2 是为model
	 */
	private function delallimg($type = 1, $idormodel) {
		if ($type == 1) {
			$delmodel = M ( 'goods_img' )->where ( array (
					'Id' => $idormodel 
			) )->find ();
		} else {
			$delmodel = $idormodel;
		}
		if ($delmodel) {
			unlink ( '.' . $delmodel ['SourceURL'] );
			unlink ( '.' . $delmodel ['URL'] );
			unlink ( '.' . $delmodel ['ThumbURL'] );
		}
	}

	/**
	 * 保存商品评论
	 *
	 * @param array $postarr:
	 *        	post数组
	 * @return array 保存信息： 包含 status 状态 ；
	 *         msg 消息
	 */
	public function addComment($postarr){
		if (empty ( $postarr )) {
			return array (
						'status' => 0,
						'msg' => '没有数据' 
						);
		}
		// 需要提交的商品评论参数
		$data = array (
				'GoodsId' => $postarr ['GoodsId'],
				'Content' => $postarr ['Content'],
				'CreateTime' => date("Y-m-d H:i:s", time()),
				'UserId' => $postarr ['UserId'],
				'AssesseeId' => $postarr ['AssesseeId'],
				'Status' => 10
		);
		$dal = M ();
		$dal->startTrans ();
		$goods_comment = M ( 'goods_comment' );
		// 保存商品评论
		$rst = $goods_comment -> add($data);
		if($rst){
			$dal->commit ();
			return array (
					'status' => 1,
					'msg' => '操作成功' 
					);
		}else {
			$dal->rollback ();
			return array (
					'status' => 0,
					'msg' => '操作失败' 
					);
		}	
	}
}

?>