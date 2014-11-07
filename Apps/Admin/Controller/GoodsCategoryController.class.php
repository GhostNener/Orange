<?php

namespace Admin\Controller;


/**
 * 后台商品分类管理
 *
 * @author NENER
 *        
 */
class GoodsCategoryController extends BaseController {
	/**
	 * 首页
	 *
	 * @author NENER
	 */
	public function index() {
		$model = M ( 'goods_category' );
		// 查询条件
		$wherrArr = array (
				'Status' => 10 
		);
		// 总数
		$allCount = $model->where ( $wherrArr )->count ();
		// 分页
		$Page = new \Think\Page ( $allCount, 30 );
		
		$showPage = $Page->show ();
		// 分页查询
		$list = $model->where ( $wherrArr )->limit ( $Page->firstRow . ',' . $Page->listRows )->select ();

		$catelist = $model->where( $wherrArr )->select();
		$this->assign( 'catelist', $catelist );
		$this->assign ( 'list', $list );
		$this->assign ( 'page', $showPage );
		$this->display ();
	}
	/**
	 * 删除
	 *
	 * @author NENER
	 */
	public function del() {
		$id = ( int ) I ( 'get.Id' );
		if (! $id) {
			$this->error ( "页面不存在" );
		}
		$whereArr = array (
				'Id' => $id 
		);
		$dal = M ();
		$dal->startTrans (); // 开始事务
		$model = M ( 'goods_category' );
		$model->Status = - 1;
		$r1 = $model->where ( $whereArr )->save (); // 操作1
		$whereArrKw = array (
				'CategoryId' => $id 
		);
		// 操作2
		$r2 = $dal->execute ( "update goods_category_keyword set Status=-1 where CategoryId=" . $id );
		if ($r1 && $r2) { // 成功
			$dal->commit (); // 提交事务
			$this-> redirect ( 'index' );
		} else {
			$dal->rollback (); // 否则回滚
			$this->error ( "操作失败" );
		}
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
			$info = 'id都没有改个屁啊！';
		}
		$whereArr = array (
				'Id' => $id 
		);
		$model = M ( 'goods_category' )->where ( $whereArr )->find ();
		if ($model) {
			$whereArrKeyword = array (
					'CategoryId' => $id 
			);

			$status = 1;
			$data = $model;
			$method = 'update';

		} else {
			$status = 0;
			$info = '没成功，活该，重新再提交';
		}

		echo json_encode ( array (
						'status' => $status,
						'info' => $info,
						'method' => $method,
						'data' => $data 
		) );
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
		$model = M ( 'goods_category' );
		$data = array (
				'Title' => I ( 'Title' ),
				'Presentation' => I ( 'Presentation' ) 
		);

		if ($modif == "add") {

			/**
			* 验证是否已存在
			* @author Cinwell
			*/
			if (M ( 'goods_category' )->where ( array (
						'Title' => $data ['Title'],
						'Status' => 10 
				) )->select ()) {
					$this->error ( "已经添加过了，逗比" );
				}

			$data ['Status'] = 10;
			$dal = M ();
			$dal->startTrans ();
			$r1 = $model->data ( $data )->add ();
			$dataKey = array (
					'CategoryId' => $r1,
					'Keyword' => $data ['Title'],
					'Status' => 10,
					'Hot' => 0 
			);
			$r2 = M ( 'goods_category_keyword' )->data ( $dataKey )->add ();
			if ($r1 && $r2) {
				$dal->commit ();
			} else {
				$dal->rollback ();
				$this->error ( "操作失败" );
			}
		} else {
			$whereArr = array (
					'Id' => ( int ) I ( "post.Id" ) 
			);
			$model->where ( $whereArr )->save ( $data );
		}
		$this->success ( '操作成功' );
	}
}