<?php

namespace Home\Controller;

use Think\Controller;
require './ORG/phpAnalysis/SeachDic.class.php';
/**
 * 词典测试
 * 
 * @author NENER
 *        
 */
class TestDicController extends Controller {
	public function index() {
		$this->assign("time",microtime (true))->display ( 'Index/testdic' );
	}
	public function dic() {
		$t1=microtime (true);
		$test = I ( 'text' );
		if (! $test) {
			$this->error ( '没有数据' );
		}
		$seach=new \SeachDic();
		$arr=$seach->seach($test);
		if(!$arr){
			return false;
		}
		foreach ($arr as $k=>$v){
			$model=M('goods_category')->where(array('Id'=>$k))->find();
			if($model){
				break;
			}
		}
		$t2=microtime (true);
		echo $model['Title'].($t2-$t1);
		
	}
}