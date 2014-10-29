<?php

namespace Home\Model;

use Think\Model;

/**
 * 商品所有
 *
 * @author NENER
 *        
 */
require_once './ORG/phpAnalysis/SearchDic.class.php';
class goods_searchModel extends Model {
	/**
	 * 用户模型自动完成
	 *
	 * @var unknown
	 */
	protected $_auto = array (
			array (
					'SearchTitle',
					'seachfc',
					self::MODEL_INSERT,
					'callback' 
			) 
	);
	/**
	 * 自动完成
	 *
	 * @param unknown $title        	
	 * @return string
	 */
	protected function seachfc($title) {
		if (! $title) {
			return '';
		}
		$sh = new \SearchDic ();
		$sh->searchdic = C ( 'SEARCH_DIC' );
		$arr = $sh->searchpart ( $title );
		if (count ( $arr ) <= 0) {
			return '';
		} else {
			$arr[]=zhCode($title);
			$rst = implode ( "  ", $arr );
			return $rst;
		}
	}
	public function addone($data) {
		$model = $this->create ( $data );
		if (! $model) {
			return $this->getDbError ();
		}
		return $this->add ( $model );
	}
}