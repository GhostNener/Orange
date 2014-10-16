<?php
ini_set ( 'display_errors', 'On' );
ini_set ( 'memory_limit', '64M' );
require './ORG/phpAnalysis/phpanalysis.class.php';
/**
 * 检索字典
 * 
 * @author NENER
 *        
 */
class SeachDic {
	
	// 岐义处理
	public $do_fork = false;
	// 新词识别
	public $do_unit = false;
	// 多元切分
	public $do_multi = false;
	// 词性标注
	public $do_prop = true;
	// 是否预载全部词条
	public $pri_dict = false;
	public function seach($str) {
		$str = strtolower ( $str );
		// 初始化类
		PhpAnalysis::$loadInit = false;
		$pa = new PhpAnalysis ( 'utf-8', 'utf-8', $this->pri_dict );
		// 载入词典
		$pa->LoadDict ();
		// 配置分词程序
		$pa->SetSource ( $str ); // 默认使用UTF8编码
		$pa->differMax = $this->do_multi; // 多元切分
		$pa->unitWord = $this->do_unit; // 新词识别
		$pa->StartAnalysis ( $this->do_fork ); // 岐义处理
		                                       // 执行分词 并返回分词结果
		$okresult = $pa->GetFinallyResult ( '$', $this->do_prop );
		return $this->StrToArr ( $okresult );
	}
	/*
	 * 对查询结果进行处理 把关键字和Id分割 去掉不存的关键字
	 */
	private function StrToArr($arr) {
		foreach ( $arr as $k => $v ) {
			$tempArr = explode ( '$|', $v );
			if ($tempArr && is_numeric ( trim ( $tempArr [1] ) )) {
				$tempArr [1] = trim ( $tempArr [1] );
				$tempArr [0] = trim ( $tempArr [0] );
				$result [$tempArr [1]] = $tempArr [0];
			}
		}
		return $result;
	}
}