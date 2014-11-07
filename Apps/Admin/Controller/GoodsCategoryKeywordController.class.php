<?php

namespace Admin\Controller;


/**
 * 分类关键字管理
 *
 * @author NENER
 *        
 */
class GoodsCategoryKeywordController extends BaseController {
	public function index() {
		$id = I ( 'CategoryId' );
		if (! $id) {
			$this->error ( "操作失败" );
		}
		// 查询条件
		$wherrArr = array (
				'Status' => 10,
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

		$catemodel = M ( 'goods_category' );
		$cateWhereArr = array(
				'Status' => 10
			);
		$catelist = $catemodel->where($cateWhereArr)->select();
		$this->assign( 'catelist', $catelist );
		$this->assign ( 'list', $list );
		$this->assign ( 'page', $showPage );
		$this->assign ( 'CategoryId', $id )->display ( 'GoodsCategory/category_keyword' );
	}

	/**
	 * 查询要修改的数据
	 *
	 * @author NENER
	 */
	public function update() {
		$id = ( int ) I ( 'get.Id' );
		if (! $id) {
			$status = 0;
			$info = "操作失败";
		}
		$whereArr = array (
				'Id' => $id 
		);
		$model = M ( 'goods_category_keyword' )->where ( $whereArr )->find ();
		if ($model) {
			$this->assign ( 'model', $model );
			$cmodel = M ( 'goods_category' )->where ( array (
					'Id' => $model ['CategoryId'] 
			) )->find ();
			
			$status = 1;
			$info = "操作失败";
			$method = "update";
			$data = $model;

		} else {
			$status = 0;
			$info = "操作失败";
		}

		echo json_encode ( array (
						'status' => $status,
						'info' => $info,
						'method' => $method,
						'data' => $data 
		) );
	}
	/**
	 * 删除
	 *
	 * @author NENER
	 */
	public function del() {
		$id = ( int ) I ( 'Id' );
		$cid = I ( 'CategoryId' );
		if (! $id) {
			$this->error ( "页面不存在" );
		}
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
			$this->success ( '操作成功' );
		}else{
			$this->error ( "操作失败" );
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
		$data = array (
				'CategoryId' => I ( 'CategoryId' ),
				'Keyword' => strtolower ( I ( 'Keyword' ) ),
				'Hot' => ( int ) I ( 'Hot' ) 
		);

		if ($modif == "add") {
			$data ['Status'] = 10;
			$data ['Hot'] = 0;
			if (M ( 'goods_category_keyword' )->where ( array (
					'Keyword' => $data ['Keyword'],
					'Status' => 10 
			) )->select ()) {
				$this->error ( "关键字已存在" );
			}
			if (! ($model->data ( $data )->add ())) {
				$this->error ( "操作失败" );
			}

		} else {

			//如果存在待审核 1 状态的，表示更新为可用 10 状态
			if (I('post.tempStatus')) {
				$data['Status'] = 10;
			}

			$whereArr = array (
					'Id' => ( int ) I ( "post.Id" ) 
			);
			$model->where ( $whereArr )->save ( $data );
		}

		$this->success ( '操作成功' );
	}

	/**
	 * 待审核的关键字列表
	 *
	 * @author Cinwell
	 */
	public function tempCateKeyList() {
		
		$whereArr = array (
				'Status' => 1
		);

		$model = M ( 'view_keyword' );
		
		// 总数
		$allCount = $model->where ( $whereArr )->count ();
		// 分页
		$Page = new \Think\Page ( $allCount, 10 );
		$showPage = $Page->show ();
		// 分页查询
		
		$list = $model->where ( $whereArr )->limit ( $Page->firstRow . ',' . $Page->listRows )->select ();

		$catemodel = M ( 'goods_category' );
		$cateWhereArr = array(
				'Status' => 10
			);
		$catelist = $catemodel->where($cateWhereArr)->select();
		$this->assign( 'catelist', $catelist );
		$this->assign ( 'list', $list );
		$this->assign ( 'page', $showPage );
		$this->display ( 'GoodsCategory/keycategory' );
	}

	/**
	 * 保存待审核关键字
	 *
	 * @author Cinwell
	 */

	public function SaveTempKey()
	{
		$data = array (
				'Status' => 10
		);

		$whereArr = array (
					'Id' => ( int ) I ( "Id" ) 
		);

		$model = M ( 'goods_category_keyword' );
		$model->where ( $whereArr )->save ( $data );
		$this->success ( '操作成功' );

	}
}