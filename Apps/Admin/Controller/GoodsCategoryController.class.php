<?php

namespace Admin\Controller;

use Think\Controller;
use Think\Model;
use Org\Util\String;

/**
 * 后台商品分类管理
 *
 * @author NENER
 *        
 */
class GoodsCategoryController extends Controller {
	/**
	 * 首页
	 *
	 * @author NENER
	 */
	public function index() {
		$model = M ( 'goods_category' );
		// 查询条件
		$wherrArr = array (
				'Status' => array (
						'eq',
						10 
				) 
		);
		// 总数
		$allCount = $model->where ( $wherrArr )->count ();
		// 分页
		$Page = new \Think\Page ( $allCount, 10 );
		$showPage = $Page->show ();
		// 分页查询
		$list = $model->where ( $wherrArr )->limit ( $Page->firstRow . ',' . $Page->listRows )->select ();
		$this->assign ( 'list', $list );
		$this->assign ( 'page', $showPage );
		$this->display ( 'Index/category' );
	}
	/**
	 * 删除
	 *
	 * @author NENER
	 */
	public function del() {
		$id = ( int ) I ( 'get.Id' );
		if ($id) {
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
				$this->success ( "操作成功", U ( 'index' ), 1 );
			} else {
				$dal->rollback (); // 否则回滚
				$this->error ( "操作失败", U ( 'index' ) );
			}
		} else {
			$this->error ( "页面不存在", U ( 'index' ) );
		}
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
			$model = M ( 'goods_category' )->where ( $whereArr )->find ();
			if ($model) {
				$whereArrKeyword = array (
						'CategoryId' => $id 
				);
				$this->assign ( 'model', $model );
				$this->assign ( 'modif', 'update' )->display ( 'Index/modifcategory' );
			} else {
				$this->error ( "操作失败", U ( 'index' ) );
			}
		} else {
			$this->error ( "操作失败", U ( 'index' ) );
		}
	}
	/**
	 * 渲染add模板
	 *
	 * @author NENER
	 *        
	 */
	public function add() {
		$this->assign ( 'modif', 'add' )->display ( 'Index/modifcategory' );
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
		$data ['Title'] = I ( 'Title' );
		$data ['Presentation'] = I ( 'Presentation' );
		if ($modif == "add") {
			$data ['Status'] = 10;
			$dal = M ();
			$dal->startTrans ();
			$r1 = $model->data ( $data )->add ();
			$dataKey ['CategoryId'] = $r1;
			$dataKey ['Keyword'] = $data ['Title'];
			$dataKey ['Status'] = 10;
			$dataKey ['Hot'] = 0;
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
		$this->success ( '操作成功', U ( 'index' ),1 );
	}
}