<?php

namespace Admin\Controller;

use Think\Controller;

/**
 * 分类数据字典
 *
 * @author NENER
 *        
 */
require './ORG/phpAnalysis/Cratedic.class.php';
class CategoryDicController extends Controller {
	/**
	 * 生成词典
	 *
	 * @author NENER
	 */
	public function crate() {
		//获取关键字数据
		$arr = M ( 'goods_category_keyword' )->where ( array (
				'Status' => 10 
		) )->select ();
		if (! $arr) {
			$this->error ( "操作失败\n没有查询到数据", U ( 'GoodsCategory/index' ) );
		}
		$cr = new \Cratedic ();
		if ($cr->buildDic ( $arr )) {
			$this->success ( "操作成功\n共" . count ( $arr ) . '个关键字', U ( 'GoodsCategory/index' ) );
		} else {
			$this->error ( '操作失败', U ( 'GoodsCategory/index' ) );
		}
	}
}