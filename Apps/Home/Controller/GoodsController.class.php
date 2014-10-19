<?php

namespace Home\Controller;

use Think\Controller;
require './ORG/phpAnalysis/SeachDic.class.php';
/**
 * 前台商品管理
 *
 * @author DongZ
 *
 */
class GoodsController extends Controller {
	public function index() {
		$userid=0;
		$model = M ( 'goods' );
		// 查询条件
		$wherrArr = array (
				'Status' =>10 ,
				'UserId'=>$userid
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
		$this->display ('index');
	}
	/**
	 * 渲染商品添加页面
	 */
	public function add() {
		$userid=0;
		// 查询分类
		$clist = M ( 'goods_category' )->where ( array (
				'Status' => 10 
		) )->select ();
		//查询地址
		$alist = M ( 'user_address' )->order('IsDefault DESC')->where ( array (
				'Status' => 10 ,'UserId'=>$userid
		) )->select ();
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
		if (empty ( $_POST )) {	$this->error ( '信息为空' );
		}
		$postarr=I('post.');
		if(!$postarr['imgcount']||!is_numeric($postarr['imgcount'])||(int)$postarr['imgcount']<=0){
			$this->error ( '没有上传图片' );
		}
		$goodsid=$postarr['GoodsId'];
		if(!$goodsid||!is_numeric($goodsid)||(int)$goodsid<=0){
			$this->error ( '操作失败' );
		}
		$keyid=I('keyid');
		//需要提交的商品参数
		$data=array(
		'Title'=>$postarr['Title'],
		'Price'=>$postarr['Price'],
		'CostPrice'=>$postarr['CostPrice'],
		'Presentation'=>$postarr['Presentation'],
		'CategoryId'=>$postarr['CategoryId'],
		'AddressId'=>$postarr['AddressId'],
		'Server'=>$postarr['Server'],
		'TradeWay'=>$postarr['TradeWay'],
		'Status'=>10
		);
		$dal=M();
		$dal->startTrans();
		$goods = M ( 'goods' );
		//保存商品订单
		$rst1 = $goods->where ( array('Id'=>$goodsid) )->save ($data);
		//修改该商品图片状态
		$rst2=M('goods_img')->where(array('GoodsId'=>$goodsid))->save(array('Status'=>10,'Title'=>$postarr['Title']));
		if(is_numeric($keyid)&&(int)$keyid>0){
			//修改新添加的关键字为审核状态
			$rst3=M('goods_category_keyword')->where(array('Id'=>$keyid))->save(array('Status'=>1,'CategoryId'=>$postarr['CategoryId']));
		}
		if($rst1&&$rst2){
			$dal->commit();
			$this->success ( 1 );
		}else {
			$dal->rollback();
			$this->error ( '添加商品失败' );
		}
	}
	/**
	 * 保存图片 添加记录
	 */
	public function saveimg() {
		if (! IS_POST) {
			$this->error ( '页面不存在' );
		}
		$goodsid = I ( '_gid' );
		$imgid = I ( '_imgid' );
		$delmodel = M ( 'goods_img' )->where ( array (
				'Id' => $imgid 
		) )->find ();
		$dal = M ();
		$dal->startTrans (); // 事务
		/*User ID 暂时还没获取*/
		$userid=0;
		if (! $goodsid || $goodsid <= 0) {
			$goodsid = M ( 'goods' )->add ( array (
					'UserId' => $userid,
					'Status' => 0 
			) );
		}
		if (!$goodsid) {
			$dal->rollback ();
			$this->delallimg($delmodel);
			$this->error ( '删除失败' );
		}
		$imgmodel = M ( 'goods_img' );
		$imgmodel->GoodsId = $goodsid;
		$rst = $imgmodel->where ( array (
					'Id' => $imgid 
		) )->save ();
		if ($rst) {
			$dal->commit ();
			$this->success ( $goodsid );
			return;
		} else {
			$dal->rollback ();
			$this->delallimg($delmodel);
			M ( 'goods_img' )->where ( array (
						'Id' => $imgid 
			) )->delete ();
			$this->error ( '删除失败' );
		}
	}
	private function delallimg($delmodel){
		if(!$delmodel){return;}
		unlink ( '.'.$delmodel['SourceURL'] );
		unlink ( '.'.$delmodel['URL'] );
		unlink ( '.'.$delmodel['ThumbURL'] );
	}
	/**
	 * 上传商品图片
	 *
	 * @author NENER 修改
	 */
	public function uploadify() {
		if (empty ( $_FILES )) {$this->error ( "页面不存在" );}
		// 载入图片上传配置
		$config = C ( 'IMG_UPLOAD_CONFIG' );
		$config['savePath']=$config['savePath'].C('GOODS_IMG_SOURCE');
		$upload = new \Think\Upload ( $config ); // 实例化上传类
		$images = $upload->upload ();
		// 判断是否有图
		if (!$images) {echo json_encode(array(0,$upload->getError()));return;}
		// 图片保存名
		$imgname = $images ['Filedata'] ['savename'];
		// 图片保存相对路径
		$imgurl = .$config ['rootPath'].$config ['savePath'] . $imgname;
		$urlarr=$this->getallthumb('.'.$imgurl,$imgname);
		$data = array (
						'GoodsId' => 0,
						'URL' => $urlarr[0],
						'ThumbURL'=>$urlarr[1],
						'SourceURL'=>$imgurl ,
						'Title' => '',
						'Status' => 0 
		);
		$rst = M ( 'goods_img' )->add ( $data );
		if ($rst) {
			echo json_encode ( array (
			$rst,
			$urlarr[1]
			) );
		} else {
			echo json_encode(array(0,$upload->getError()));
		}
	}
	/**
	*压缩图像  返回路径
	*/
	private function getallthumb($url,$imgname){
		$rooturl=C('GOODS_IMG_ROOT');
		$url_8=.$rooturl.C('GOODS_IMG_800').$imgname;
		$url_1=.$rooturl.C('GOODS_IMG_100').$imgname;
		$imagedal = new \Think\Image();
		$imagedal->open($url);
		$size = $imagedal->size();
		$imagedal->thumb(800, 800)->save('.'.$url_8);
		$imagedal->thumb(100, 100,\Think\Image::IMAGE_THUMB_CENTER)->save('.'.$url_1);
		return array($url_8,$url_1);
	}
	/**
	 * 删除图片
	 */
	public function delimg() {
		if (! IS_POST) {
			$this->error ( "页面不存在" );
		}
		if (!I('Id')) {
			// 没有获得要删除的图片
			$this->error ( "没有获得要删除的图片" );
		}
		$rst = M ( 'goods_img' )->where ( array (
				'Id' => (int)I('Id') 
		) )->find();
		if (! $rst||!M ( 'goods_img' )->where ( array (
				'Id' => (int)I('Id') 
		) )->delete()) {
			$this->error ( "删除失败" );
			return;
		}
		$this->delallimg($rst);
		$this->success ( 1 );
	}
	/**
	*获得分类
	*/
	public function getcategory(){
		if(!IS_POST){
			$this->error ( "页面不存在" );
		}
		$str=I('Title');
		if(!$str){
			$this->error (0);
		}
		$seach = new \SeachDic ();
		$arr = $seach->seach ( $str );
		if (! $arr) {
			//如果查不到 则添加一个临时记录
			if (!M ( 'goods_category_keyword' )->where ( array (
					'Keyword' => strtolower ($str),
					'Status' => array('gt',-1)
			) )->find()) {
				//不存在相同关键字 就插入新纪录
				$keyid=M('goods_category_keyword')->data(array('CategoryId'=>0,'Keyword'=>strtolower ($str),'Status'=>0))->add();
			}
			if(!$keyid){
				$keyid=0;
			}
			//返回第一个参数0 表示查不到 并返回一个Id分类
			$this->error(json_encode(array(0,$keyid)));
		}
		foreach ( $arr as $k => $v ) {
			$model[] = M ( 'goods_category' )->field(array('Id','Title'))->where ( array (
					'Id' => $k ,
					'Status'=>10
			) )->find ();
		}
		/*如果数据库和字典不一致
		 * 字典中存在，数据库不存在  则做处理
		 * */
		if(!$model){
			if (!M ( 'goods_category_keyword' )->where ( array (
					'Keyword' => strtolower ($str),
					'Status' => array('gt',-1)
			) )->find()) {
				$keyid=M('goods_category_keyword')->data(array('CategoryId'=>0,'Keyword'=>strtolower ($str),'Status'=>0))->add();
			}
			if(!$keyid){
				$keyid=0;
			}
			$this->error(json_encode(array(0,$keyid)));
		}
		//如果只查到一个  就返回Id
		if(count($model)==1){
			//返回 第一个参数1表示只查到一个
			$this->success(json_encode(array(1,(int)$model[0]['Id'])));
		}
		$wherearr=array('Status'=>10);
		//动态生成where
		foreach ($model as $key => $value) {
			$wherearr[]=array('Id'=>array('neq',$value['Id']));
		}
		$clist=M('goods_category')->field(array('Id','Title'))->where($wherearr)->select();
		if($clist){
			$newlist=array_merge($model,$clist);
		}
		$this->success( json_encode(array(2,$newlist)));
	}
	/**
	 * 刷新地址
	 * */
	public function refreshadd() {
		if(!IS_POST){
			$this->error('页面不存在');
		}
		$userid=0;
		$arr=M ( 'user_address' )->order('IsDefault DESC')->where ( array (
				'Status' => 10 ,'UserId'=>$userid
		) )->select ();
		$this->success(json_encode($arr));
	}
}