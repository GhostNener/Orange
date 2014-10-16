<?php

namespace Home\Controller;

use Think\Controller;

/**
 * 前台商品管理
 *
 * @author DongZ
 *        
 */
class GoodsController extends Controller {
	public function index() {
		$this->display ();
	}
	/**
	 * 渲染商品添加页面
	 */
	public function add() {
		//查询分类
		$clist=M('goods_category')->where(array('Status'=>10))->select();
		cookie ( 'GoodsId', '', time () - 600 );
		cookie ( 'GoodsId', - 1, 600 );
		$time = date ( DATE_RFC822 );
		$this->assign ( 'time', $time );
		$this->assign('clist',$clist)->display ('modifgoods');
	}
	/**
	 * 保存
	 */
	public function save() {
		if (!IS_POST) {
			$this->error ( '页面不存在' );
		}
		if (! empty ( $_POST )) {
			$goods = M ( 'goods' );
			$goodsid = cookie ( 'GoodsId' );
			$where ['Id'] = $goodsid;
			$z = $goods->where ( $where )->save ();
			if ($z) {
				cookie ( 'GoodsId', '', time () - 600 );
				cookie ( 'GoodsId', - 1, 600 );
				$this->success ( '添加商品成功' );
			} else {
				$this->error ( '添加商品失败' );
			}
		}else {
			$this->error ( '信息为空' );
		}
	}
	
	/**
	 * 上传商品图片
	 *
	 * @author NENER 修改
	 */
	public function uploadify() {
		if (! empty ( $_FILES )) {
			// 载入图片上传配置
			$config = C ( 'IMG_UPLOAD_CONFIG' );
			$upload = new \Think\Upload ( $config ); // 实例化上传类
			$images = $upload->upload ();
			// 判断是否有图
			if ($images) {
				// 判断COOKIE
				if (! cookie ( 'GoodsId' ) || ( int ) cookie ( 'GoodsId' ) <= 0) {
					// 插入临时的商品记录
					$rstg = M ( 'goods' )->data ( array (
							'UserId' => 5,
							'Status' => 0 
					) )->add ();
					if ($rstg) {
						// 设置cookie
						cookie ( 'GoodsId', ( int ) $rstg, 600 );
						$goodsid = $rstg;
					} else {
						$this->error ( "error" );
						return;
					}
				}
				// 获得商品ID
				$goodsid = cookie ( 'GoodsId' );
				// 图片保存名
				$imgname = $images ['Filedata'] ['savename'];
				// 图片保存路径
				$imgurl = $config ['savePath'] . $imgname;
				$data = array (
						'GoodsId' => $goodsid,
						'URL' => $imgurl,
						'Title' => $imgname,
						'Status' => 0 
				);
				if (M ( 'goods_img' )->add ( $data )) {
					echo $imgurl;
				} else {
					$this->error ( "error" );
				}
			} else {
				$this->error ( $upload->getError () );
			}
		} else {
			$this->error ( "页面不存在" );
		}
	}
	
	/**
	 * 删除图片
	 */
	public function delimg() {
		if(!IS_POST){
			$this->error ( "页面不存在" );
		}
		if (! I ( 'URL' )) {
			// 没有获得要删除的图片
			$this->error ( "没有获得要删除的图片" );
		}		
		$giurl = I ( 'URL' );
		$url = './Public/' . $giurl;
		$rst = M ( 'goods_img' )->where ( array (
				'URL' => $giurl 
		) )->delete ();
		if (! $rst) {
			$this->error ( "删除失败" );
			return;
		}
		unlink ( $url );
		$this->success ( "成功" );
	}
}