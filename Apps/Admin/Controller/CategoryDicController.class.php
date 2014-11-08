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
		$searchm = M ( 'goods_category' )->where ( array (
				'Title' => C ( 'SEARCH_CATEGORY_NAME' ) 
		) )->find ();
		/* 分类关键字 */
		$arr = M ( 'goods_category_keyword' )->where ( array (
				'Status' => 10,
				'CategoryId' => array (
						'neq',
						$searchm ['Id'] 
				) 
		) )->select ();
		/* 搜索关键字 */
		$arrseach = M ( 'goods_category_keyword' )->where ( array (
				'Status' => 10,
				'CategoryId' => $searchm ['Id'] 
		) )->select ();
		if (! $arr) {
			$this->error ( "操作失败\n没有查询到数据" );
		}
		$cr = new \Cratedic ();
		$cr->categorydic = C ( 'CATEGOEY_DIC' );
		$cr->seachdic = C ( 'SEARCH_DIC' );
		$rst = $cr->buildDic ( $arr, $arrseach );
		if ($rst) {
			if (! $searchm) {
				$warmmsg = '但系统没有建立搜索词库！';
			}
			$this->success ( "操作成功," . $warmmsg . "\n共" . count ( $arr ) + count ( $arrseach ) . "个关键字" );
		} else {
			$this->error ( '操作失败' );
		}
	}
}