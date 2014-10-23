<?php

namespace Home\Model;

use Think\Model;

/**
 * 商品分类模型
 *
 * @author NENER
 *        
 */
require_once  './ORG/phpAnalysis/SeachDic.class.php';
class goods_categoryModel extends Model {
	/**
	 * 获取所有分类
	 *
	 * @return array：分类列表
	 */
	public function getall() {
		$arr = M ( 'goods_category' )->field ( array (
				'Id',
				'Title' 
		) )->where ( array (
				'Status' => 10 
		) )->select ();
		return $arr;
	}
	
	/**
	 * 根据标题回去分类列表
	 *
	 * @param string $str：标题        	
	 * @return array：status，msg
	 */
	public function getcategory($str) {
		if (! $str) {
			return array (
					'status' => 0,
					'msg' => '数据为空' 
			);
		}
		$seach = new \SeachDic ();
		$arr = $seach->seach ( $str );
		$other = M ( 'goods_category' )->field ( array (
				'Id',
				'Title' 
		) )->where ( array (
				'Title' => '其它',
				'Status' => 10 
		) )->find ();
		if (! $other) {
			return array (
					'status' => 0,
					'msg' => '没有其他分类' 
			);
		}
		if (! $arr) {
			// 如果查不到 则添加一个临时记录
			if (! M ( 'goods_category_keyword' )->where ( array (
					'Keyword' => strtolower ( $str ),
					'Status' => array (
							'gt',
							- 1 
					) 
			) )->find ()) {
				// 不存在相同关键字 就插入新纪录
				$keyid = M ( 'goods_category_keyword' )->data ( array (
						'CategoryId' => 0,
						'Keyword' => strtolower ( $str ),
						'Status' => 0 
				) )->add ();
			}
			if (! $keyid) {
				$keyid = 0;
			}
			$clist = M ( 'goods_category' )->field ( array (
					'Id',
					'Title' 
			) )->where ( array (
					'Id' => array (
							'neq',
							$other ['Id'] 
					) 
			) )->select ();
			$newlist = array_merge ( array (
					$other 
			), $clist );
			return array (
					'status' => 1,
					'msg' => $newlist 
			);
		}
		foreach ( $arr as $k => $v ) {
			$model [] = M ( 'goods_category' )->field ( array (
					'Id',
					'Title' 
			) )->where ( array (
					'Id' => $k,
					'Status' => 10 
			) )->find ();
		}
		/*
		 * 如果数据库和字典不一致 字典中存在，数据库不存在 则做处理
		 */
		if (! $model) {
			if (! M ( 'goods_category_keyword' )->where ( array (
					'Keyword' => strtolower ( $str ),
					'Status' => array (
							'gt',
							- 1 
					) 
			) )->find ()) {
				$keyid = M ( 'goods_category_keyword' )->data ( array (
						'CategoryId' => 0,
						'Keyword' => strtolower ( $str ),
						'Status' => 0 
				) )->add ();
			}
			if (! $keyid) {
				$keyid = 0;
			}
			return array (
					'status' => 1,
					'msg' => $newlist 
			);
		}
		$wherearr = array (
				'Status' => 10 
		);
		// 动态生成where
		foreach ( $model as $key => $value ) {
			$wherearr [] = array (
					'Id' => array (
							'neq',
							$value ['Id'] 
					) 
			);
		}
		$clist = M ( 'goods_category' )->field ( array (
				'Id',
				'Title' 
		) )->where ( $wherearr )->select ();
		if ($clist) {
			$newlist = array_merge ( $model, $clist );
		}
		return array (
				'status' => 1,
				'msg' => $newlist 
		);
	}
}

?>