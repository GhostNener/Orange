<?php

namespace Admin\Controller;

set_time_limit ( 30 );
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
		/* 分类关键字 */
		$arr = M ( 'goods_category_keyword' )->where ( array (
				'Status' => 10 
		) )->select ();
		if (! $arr) {
			$this->error ( "操作失败\n没有查询到数据" );
		}
		$cr = new \Cratedic ();
		$cr->categorydic = C ( 'CATEGOEY_DIC' );
		$rst = $cr->buildDic ( $arr );
		if ($rst) {
			
			$this->success ( "操作成功,\n共" . count ( $arr ) . "个关键字" );
		} else {
			$this->error ( '操作失败' );
		}
	}
}