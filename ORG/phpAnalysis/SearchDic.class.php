<?php
ini_set ( 'display_errors', 'On' );
ini_set ( 'memory_limit', '64M' );
require_once './ORG/phpAnalysis/phpanalysis.class.php';
/**
 * 检索字典
 *
 * @author NENER
 *        
 */
class SearchDic {
	
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
	
	/* 分类词典路径 */
	public $categorydic = 'dict/category_dic_full.dic';
	/* 全文检索词典路径 */
	public $searchdic = 'dict/search_dic_full.dic';
	/**
	 * 搜索分类
	 *
	 * @param array $str
	 *        	标题
	 */
	public function search($str) {
		$str = strtolower ( $str );
		PhpAnalysis::$loadInit = false;
		$pa = new PhpAnalysis ( 'utf-8', 'utf-8', $this->pri_dict, '', $this->categorydic );
		$pa->LoadDict ();
		$pa->SetSource ( $str ); /* 默认使用UTF8编码 */
		$pa->differMax = $this->do_multi; /* 多元切分 */
		$pa->unitWord = $this->do_unit; /* 新词识别 */
		$pa->StartAnalysis ( $this->do_fork ); /* 岐义处理 */
		$okresult = $pa->GetFinallyResult ( '$', $this->do_prop );
		return $this->StrToArr ( $okresult );
	}
	/**
	 * 对查询结果进行处理 把关键字和Id分割 去掉不存的关键字
	 *
	 * @param string $arr
	 *        	分词数组
	 * @return array
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