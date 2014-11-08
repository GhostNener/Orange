<?php

namespace Home\Controller;

use Think\Controller;

require_once './ORG/phpAnalysis/SearchDic.class.php';
/**
 * 词典测试
 *
 * @author NENER
 *        
 */
class TestDicController extends Controller {
	public function index() {
		$this->assign ( "time", microtime ( true ) )->display ( 'Index/testdic' );
	}
	public function pypage() {
		$this->assign ( "time", microtime ( true ) )->display ( 'Index/testpy' );
	}
	public function sppage() {
		$this->assign ( "time", microtime ( true ) )->display ( 'Index/testsp' );
	}
	public function dic() {
		$t1 = microtime ( true );
		$test = I ( 'text' );
		if (! $test) {
			$this->error ( '没有数据' );
		}
		$search = new \SearchDic ();
		$arr = $search->search ( $test );
		if (! $arr) {
			return false;
		}
		foreach ( $arr as $k => $v ) {
			$model = M ( 'goods_category' )->where ( array (
					'Id' => $k 
			) )->find ();
			if ($model) {
				break;
			}
		}
		$t2 = microtime ( true );
		echo $model ['Title'] . '<br>耗时：' . ($t2 - $t1);
	}
	public function sp() {
		$t1 = microtime ( true );
		$test = I ( 'text' );
		if (! $test) {
			$this->error ( '没有数据' );
		}
		$arr = searchpart ( $test );
		$arr = implode ( '<br>', $arr );
		$t2 = microtime ( true );
		echo $arr . '<br>耗时：' . ($t2 - $t1);
	}
	public function py() {
		$t1 = microtime ( true );
		$test = I ( 'text' );
		if (! $test) {
			$this->error ( '没有数据' );
		}
		$rst = Pinyin ( $test );
		$t2 = microtime ( true );
		echo $rst . '<br>耗时：' . ($t2 - $t1);
	}
}