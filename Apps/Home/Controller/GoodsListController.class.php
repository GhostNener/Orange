<?php

namespace Home\Controller;

use Think\Controller;
use Home\Model\goodsModel;
use Home\Model\goods_categoryModel;
use Usercenter\Model\user_addressModel;
use Home\Model\goods_serviceModel;

class GoodsListController extends Controller {
	public function index() {
		$model = D ( 'goods' );
		// 查询条件
		$wherrArr = array (
				'Status' => 10,
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
		$userid = cookie('_uid');
		// 查询分类
		$clist = new goods_categoryModel ();
		$clist = $clist->getall ();
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
		if (( int ) $rst ['status'] == 0) {
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
		$userid = cookie('_uid');
		$postarr = I ( 'post.' );
		$model = new goodsModel ();
		$rst = $model->saveimg ( $postarr, $userid );
		if (( int ) $rst ['status'] == 0) {
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
		if (( int ) $rst ['status'] == 0) {
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
		if (( int ) $rst ['status'] == 0) {
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
		if (( int ) $rst ['status'] == 1) {
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
		$userid = cookie('_uid');
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
		$goods = M("goods");
		$wherrArr = array(
				'g.Id'=>$Id
		);
		$model=$goods->table('goods g,goods_img i')->where(array($wherrArr,'i.GoodsId=g.Id'))->field('i.URL as imgURL,g.*')->select();
		$this -> assign('model', $model);
       //评论
       $goods_comment = D("goods_comment");
		// 评论查询条件
		$wherrArr = array (
				'c.GoodsId'=>$Id,
				'c.Status' =>10,
		);
		// 查询
		$allComment = $goods_comment -> table('goods_comment c,user u') -> where(array($wherrArr,'u.Id=c.UserId')) ->field('u.Nick as UserNick,c.*') ->select ();
        $this -> assign('allComment', $allComment);

//		//评论
//		$goods_comment = M("goods_comment");
//		
        
        $this -> display();
	}
	
	/**
	 *添加评论
	 */
	public function addComment(){
		$postarr = I ( 'post.' );
		$model = new goodsModel ();
		$rst = $model->addComment ( $postarr );
		if (( int ) $rst ['status'] == 0) {
			$this->error ( $rst ['msg'] );
		} else {
			$this->success ( 1 );
		}
	}
	
	/**
	 *  购买  生成表单
	 */
	public function order($Id)
	{	
		$goods = M("goods");
		$info = $goods->find($Id);
		$this-> assign('info', $info);
		
		$seller = $info['UserId'];
		$User = M("user");
		$user = $User->find($seller);
		$this->assign('user', $user);
		
		$Addr = $info['AddressId'];
		$user_Address = M("user_address"); 
		$cate = $user_Address->find($Addr);
        $this -> assign('cate', $cate);
        
		$this->display();
	}
	
	/**
	 *  购买  生成表单
	 */
	public function order2()
	{
		$goods_order = M("goods_order");
		$data = array (
				'BuyerId' => $_POST['BuyerId'],
				'BuyerAddId'=> $_POST['BuyerAddId'],
				'SellerId'=> $_POST['SellerId'],
				'SellerAddId'=> $_POST['SellerAddId'],
				'GoodsId'=> $_POST['GoodsId'],
				'Price' => $_POST['Price'],
				'E-Money' => $_POST['E-Money'],
				'CreateTime'=> date("Y-m-d H:i:s", time()),
				'UserId'=> cookie('_uid'),
				//'AssesseId' => $_POST['AssesseId'],

				'Status' => 10
		);
		$z = $goods_order->add($data);
		if($z){
			$goods = M("goods");
			$wh = array(
					'Id' => $_POST['GoodsId']
			);
			$goods->where($wh)->setField('Status',2);
			echo "成功";
			//$this->redirect('Goods/showgoods',array('Id'=>$data['GoodsId']),0,'');
        } else {
            echo "error";
        }
	}
}
