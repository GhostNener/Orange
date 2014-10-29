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
	 * 自动验证
	 *
	 * @var unknown
	 */
	protected $_validate = array (
			array (
					'GoodsId',
					'',
					'已存在！',
					self::EXISTS_VALIDATE,
					'unique' 
			));


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
			) ,
			array (
					'SourceTitle',
					'seachfcnoc',
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
			$rst = implode ( ' ', $arr );
			$rst2=implode ( '', $arr );
			$rst=$rst.' '.$rst2;
			return $rst;
		}
	}
		/**
	 * 自动完成
	 *
	 * @param unknown $title        	
	 * @return string
	 */
	protected function seachfcnoc($title) {
		if (! $title) {
			return '';
		}
		$sh = new \SearchDic ();
		$sh->searchdic = C ( 'SEARCH_DIC' );
		$arr = $sh->searchpart ( $title ,false);
		if (count ( $arr ) <= 0) {
			return '';
		} else {
			$rst = implode ( ' ', $arr );
			return $rst;
		}
	}
	public function saveone($data) {
		$data['SourceTitle']=$data['SearchTitle'];
		$model = $this->create ( $data );
		if (! $model) {
			$this->where(array('GoodsId'=>$data['GoodsId']))->save( $data );
		}else{
			$this->add ( $model );
		}
	}
}