<?php

namespace Admin\Controller;

use Think\Controller;

/**
 * 分类关键字管理
 *
 * @author NENER
 *        
 */
class GoodsCategoryKeywordController extends Controller {
	public function index() {
		$id = I ( 'CategoryId' );
		if (! $id) {
			$this->error ( "操作失败" );
		}
		// 查询条件
		$wherrArr = array (
				'Status' => array (
						'eq',
						10 
				),
				'CategoryId' => $id 
		);
		$cmodel = M ( 'goods_category' )->where ( array (
				'Id' => $id 
		) )->find ();
		$this->assign ( 'cmodel', $cmodel );
		$model = M ( 'goods_category_keyword' );
		
		// 总数
		$allCount = $model->where ( $wherrArr )->count ();
		// 分页
		$Page = new \Think\Page ( $allCount, 10 );
		$showPage = $Page->show ();
		// 分页查询
		
		$list = $model->where ( $wherrArr )->limit ( $Page->firstRow . ',' . $Page->listRows )->select ();
		$this->assign ( 'list', $list );
		$this->assign ( 'page', $showPage );
		$this->assign ( 'CategoryId', $id )->display ( 'index/category_keyword' );
	}
	/**
	 * 渲染add模板
	 *
	 * @author NENER
	 *        
	 */
	public function add() {
		$id = I ( 'CategoryId' );
		if (! $id) {
			$this->error ( "操作失败" );
		}
		$cmodel = M ( 'goods_category' )->where ( array (
				'Id' => $id 
		) )->find ();
		$this->assign ( 'cmodel', $cmodel );
		$this->assign ( 'modif', 'add' )->display ( 'index/modifcategory_keyword' );
	}
	/**
	 * 查询要修改的数据
	 *
	 * @author NENER
	 */
	public function update() {
		$id = ( int ) I ( 'get.Id' );
		if ($id) {
			$whereArr = array (
					'Id' => $id 
			);
			$model = M ( 'goods_category_keyword' )->where ( $whereArr )->find ();
			if ($model) {
				$this->assign ( 'model', $model );
				$cmodel = M ( 'goods_category' )->where ( array (
						'Id' => $model ['CategoryId'] 
				) )->find ();
				$this->assign ( 'cmodel', $cmodel );
				$this->assign ( 'modif', 'update' )->display ( 'index/modifcategory_keyword' );
			} else {
				$this->error ( "操作失败", U ( 'index' ) );
			}
		} else {
			$this->error ( "操作失败", U ( 'index' ) );
		}
	}
	/**
	 * 删除
	 *
	 * @author NENER
	 */
	public function del() {
		$id = ( int ) I ( 'get.Id' );
		$cid = I ( 'CategoryId' );
		if ($id) {
			$whereArr = array (
					'Id' => $id 
			);
			$model = M ( 'goods_category_keyword' );
			$count = $model->where ( array (
					'CategoryId' => $cid,
					'Status' => 10 
			) )->count ();
			
			if ($count <= 1) {
				$this->error ( "至少保留一个关键字" );
			}
			if ($model->where ( $whereArr )->delete ()) {
				$this->success ( "操作成功" );
			}
		} else {
			$this->error ( "页面不存在" );
		}
	}
	/**
	 * 保存
	 * 包含更新 ，添加
	 *
	 * @author NENER
	 */
	public function save() {
		if (! IS_POST) {
			$this->error ( "页面不存在" );
		}
		$modifArr = array (
				"add",
				"update" 
		);
		$modif = strtolower ( I ( 'post.modif' ) );
		if (! in_array ( $modif, $modifArr )) {
			$this->error ( "非法操作" );
		}
		$model = M ( 'goods_category_keyword' );
		$data ['CategoryId'] = I ( 'CategoryId' );
		$data ['Keyword'] = I ( 'Keyword' );
		$data ['Hot'] = ( int ) I ( 'Hot' );
		if ($modif == "add") {
			$data ['Status'] = 10;
			$data ['Hot'] = 0;
			if (M ( 'goods_category_keyword' )->where ( array (
					'Keyword' => $data ['Keyword'] 
			) )->select ()) {
				$this->error ( "关键字已存在" );
			}
			if (! ($model->data ( $data )->add ())) {
				$this->error ( "操作失败" );
			}
		} else {
			$whereArr = array (
					'Id' => ( int ) I ( "post.Id" ) 
			);
			$model->where ( $whereArr )->save ( $data );
		}
		$this->success ( '操作成功', U ( 'index', array (
				'CategoryId' => $data ['CategoryId'] 
		) ) );
	}
}