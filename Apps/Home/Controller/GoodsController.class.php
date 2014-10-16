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

	/**
	 * 上传商品首页
	 *
	 */
	public  function  addIndex(){
				cookie('GoodsId','',time()-600);
		cookie('GoodsId',-1,600);
		header("Content-Type:text/html; charset=utf-8");
		$time=date(DATE_RFC822);
		$this->assign('time',$time);
		$this ->assign('cookie',cookie('GoodsId'))-> display();
	}

	/**
	 * 添加
	 * Enter description here ...
	 */
	public function add(){
		if(!empty($_POST)){
			$goods =M('goods');
			$goodsid = cookie('GoodsId');
			$goods -> create();
			$where['Id']=$goodsid;
			$z = $goods ->where($where) -> save();
			if($z){
				//$this ->success('添加商品成功', U('Goods/showlist'));
				cookie('GoodsId','');
				echo "success";
			} else {
				//$this ->error('添加商品失败', U('Goods/showlist'));
				echo "error";
			}
		}
	}

	/**
	 *上传图片
	 * Enter description here ...
	 */
	public function uploadify(){
		$fileSN=microtime(true);
		$fileSN=str_replace('.', '', $fileSN);
		if (!empty($_FILES)) {
			//图片上传设置
			$config = array(
                'maxSize'    =>    3145728, 
                'rootPath'	 =>    'Public',
                'savePath'   =>    '/Uploads/',  
                'saveName'   =>   $fileSN, 
                'exts'       =>    array('jpg', 'gif', 'png', 'jpeg'),  
                'autoSub'    =>    false,   
                'subName'    =>    array('date','Y-m-d'),
            'hash'=>false,
			);
			$upload = new \Think\Upload($config);// 实例化上传类
			$images = $upload->upload();
			//判断是否有图
			if($images){
				if(!cookie('GoodsId')||(int)cookie('GoodsId')<=0){
					$gmodel=M('goods')->data(array(
					'UserId'=>5,
					'Status'=>0
					))->add();
					if($gmodel){
						cookie('GoodsId',(int)$gmodel,600);
						$goodsid=$gmodel;
					}
					$goodsid=$gmodel;
				}
				$goodsid = cookie('GoodsId');
				$info=$images['Filedata']['savename'];
				$bigimg = $config['savePath'].$info;
				$ar = array(
           			'GoodsId'	=>	$goodsid,
            		'URL'	=>	$bigimg,
            		'Title'	=>  $info,
					'Status' => 0,
				);
				if(($rst = M('goods_img') -> add($ar))){
					//返回文件地址和名给JS作回调用
					echo $bigimg;
				}else {
					$this->error("error");
				}
			}
			else{
				$this->error($upload->getError());//获取失败信息
			}
		}
	}

	/**
	 * 删除图片
	 * Enter description here ...
	 */
	public function del(){
		if($_POST['name']!=""){
			$info = I('name');
		//count($info)
			$url = './Public/'.$info;
//
//			$ur = $_POST['name'];
		$img = M('goods_img');
//			$where['Title']=$ur;
			$img->where(array('URL'=>$info))->delete();

			if(unlink($url)){
				$this->success("成功");
			}
			else{//删除失败
				$this->error("unlink fail");
			}
		}
		else{//没有获得要删除的图片
			$this->error("info is gap");
		}
	}
}