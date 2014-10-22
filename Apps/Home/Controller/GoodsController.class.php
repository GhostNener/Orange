<?php

namespace Home\Controller;

use Think\Controller;
use Home\Model\goodsModel;
use Home\Model\goods_categoryModel;
use Home\Model\user_addressModel;
use Home\Model\goods_serviceModel;


/**
 * 前台商品管理
 *
 * @author DongZ
 *        
 */
class GoodsController extends Controller {
	public function index() {
		$userid = 0;
		$model = D ( 'goods' );
		// 查询条件
		$wherrArr = array (
				'Status' => 10,
				'UserId' => $userid 
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
		$this->display ( 'index' );
	}
	/**
	 * 渲染商品添加页面
	 */
	public function add() {
		$userid = 0;
		// 查询分类
		$clist = new goods_categoryModel();
		$clist=$clist->getall();
		// 查询地址
		$alist = D ( 'user_address' )->order ( 'IsDefault DESC' )->where ( array (
				'Status' => 10,
				'UserId' => $userid 
		) )->select ();
		$g_smodel = new goods_serviceModel ();
		$slist = $g_smodel->getall ();
		$this->assign ( 'slist', $slist );
		$this->assign ( 'alist', $alist );
		$this->assign ( 'clist', $clist )->display ( 'modifgoods' );
	}
	/**
	 * 保存
	 */
	public function save() {
		if (! IS_POST) {
			$this->error ( '页面不存在' );
		}
		$postarr = I ( 'post.' );
		$model = new goodsModel ();
		$rst = $model->save ( $postarr );
		if ((int)$rst ['status'] == 0) {
			$this->error ( $rst ['msg'] );
		} else {
			$this->success ( 1 );
		}
	}
	/**
	 * 保存图片 添加记录
	 */
	public function saveimg() {
		if (! IS_POST) {
			$this->error ( '页面不存在' );
		}
		$userid = 0;
		$postarr = I ( 'post.' );
		$model = new goodsModel ();
		$rst = $model->saveimg ( $postarr, $userid );
		if ((int)$rst ['status'] == 0) {
			$this->error ( $rst ['msg'] );
		} else {
			$this->success ( ( int ) $rst ['goodsid'] );
		}
	}
	/**
	 * 上传商品图片
	 *
	 * @author NENER 修改
	 */
	public function uploadify() {
		if (empty ( $_FILES )) {
			$this->error ( "页面不存在" );
		}
		$model = new goodsModel ();
		$rst = $model->uploadimg ();
		if ((int)$rst ['status'] == 0) {
			echo json_encode ( array (
					0,
					$rst ['msg'] 
			) );
		} else {
			echo json_encode ( array (
					$rst ['imgid'],
					$rst ['msg'] 
			) );
		}
	}
	/**
	 * 删除图片
	 */
	public function delimg() {
		if (! IS_POST) {
			$this->error ( "页面不存在" );
		}
		if (! I ( 'Id' )) {
			// 没有获得要删除的图片
			$this->error ( "没有获得要删除的图片" );
		}
		$model = new goodsModel ();
		$rst = $model->delimg ( ( int ) I ( 'Id' ) );
		if ((int)$rst ['status'] == 0) {
			$this->error ( $rst ['msg'] );
		} else {
			$this->success ( 1 );
		}
	}
	/**
	 * 获得分类
	 */
	public function getcategory() {
		if (! IS_POST) {
			$this->error ( "页面不存在" );
		}
		$str = I ( 'Title' );
		if (! $str) {
			$this->error ( 0 );
		}
		$model = new goods_categoryModel ();
		$rst = $model->getcategory ( $str );
		if ((int)$rst ['status'] == 1) {
			$this->success ( json_encode ( array (
					2,
					$rst ['msg'] 
			) ) );
		} else {
			$this->error ( json_encode ( array (
					0,
					$rst ['msg'] 
			) ) );
		}
	}
	/**
	 * 刷新地址
	 */
	public function refreshadd() {
		if (! IS_POST) {
			$this->error ( '页面不存在' );
		}
		$userid = 0;
		$model = new user_addressModel ();
		$rst = $model->getall ( $userid );
		if (! $rst) {
			$this->error ( json_encode ( 0 ) );
		}
		$this->success ( json_encode ( $rst ) );
	}
	
	/**
	 *展示商品 详情 及评论
	 */
	public function showgoods($Id){
		$goods = D("goods");
		$info = $goods->find($Id); //一维数组
		$this -> assign('info', $info);
				
		//类别
		$goods_category = D("goods_category");
		$categoryId = $info['CategoryId'];
		$cate = $goods_category->find($categoryId);
        $this -> assign('cate', $cate);
        
        //评论
        $goods_comment = D("goods_comment");
		// 评论查询条件
		$wherrArr = array (
				'GoodsId'=>$Id,
				'Status' =>10,
		);
		// 查询
		$allComment = $goods_comment->where ( $wherrArr )->select ();
        $this -> assign('allComment', $allComment);
        $this -> display();
	}
	
	/**
	 *添加评论
	 */
	public function addComment(){
		$goods_Comment = M("goods_comment");
		$data = array (
				'GoodsId' => $_POST['GoodsId'],
				'Content' => $_POST['Content'],
				'CreateTime'=> date("Y-m-d H:i:s", time()),
				'UserId'=> $_POST['UserId'],
				//'AssesseId' => $_POST['AssesseId'],
				'Status' => 10, 
		);
		$z = $goods_Comment->add($data);
		if($z){
            //$this ->success('添加成功', U('Goods/showlist'));
            echo "success";
        } else {
            //$this ->error('添加失败', U('Goods/showlist'));
            echo "ddderror";
        }
	}
}