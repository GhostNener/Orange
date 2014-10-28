<?php

namespace Admin\Controller;

/**
 * 分类数据字典
 *
 * @author NENER
 *        
 */
require './ORG/phpAnalysis/Cratedic.class.php';
class CategoryDicController extends BaseController {
	/**
	 * 生成词典
	 *
	 * @author NENER
	 */
	public function crate() {
		// 获取关键字数据
		$arr = M ( 'goods_category_keyword' )->where ( array (
				'Status' => 10 
		) )->select ();
		if (! $arr) {
			$this->error ( "操作失败\n没有查询到数据", U ( 'GoodsCategory/index' ) );
		}
		$cr = new \Cratedic ();
		$cr->categorydic = C ( 'CATEGOEY_DIC' );
		$cr->seachdic = C ( 'SEACH_DIC' );
		if ($cr->buildDic ( $arr )) {
			$this->success ( "操作成功\n共" . count ( $arr ) . '个关键字', U ( 'GoodsCategory/index' ), 1 );
		} else {
			$this->error ( '操作失败', U ( 'GoodsCategory/index' ) );
		}
	}
}