<?php
ini_set ( 'memory_limit', '128M' );
require './ORG/phpAnalysis/phpanalysis.class.php';
/**
 * 分类字典生成
 *
 * @author NENER
 *        
 */
class Cratedic {
	/* 数据库导出词典txt路径 */
	public $dictxt = '/dict/txt/category_dic_full.txt';
	/* 分类词典路径 */
	public $categorydic = 'dict/category_dic_full.dic';
	/* 全文检索词典 路径 */
	public $seachdic = 'dict/seach_dic_full.dic';
	/**
	 * 编译词典
	 *
	 * @param array $arr
	 *        	数据库关键字数据
	 * @return boolean
	 */
	public function buildDic($arr) {
		// txt文件路径
		$txtpath = dirname ( __FILE__ ) . $this->dictxt;
		if (! ($this->expotrTxt ( $txtpath, $arr ))) {
			return false;
		}
		/* 生成关键字词典 */
		PhpAnalysis::$loadInit = false;
		$pa = new PhpAnalysis ( 'utf-8', 'utf-8', false, '', $this->categorydic );
		$pa->MakeDict ( $txtpath );
		/* 生成全文检索词典 */
		$pa = new PhpAnalysis ( 'utf-8', 'utf-8', false, '', $this->seachdic );
		$pa->MakeDict ( $txtpath );
		return true;
		exit ();
	}
	/**
	 * 关键字数据导出文本txt
	 *
	 * @param string $txtPath
	 *        	导出的文件路径
	 * @param array $arr关键字数据        	
	 * @return boolean
	 */
	private function expotrTxt($txtPath, $arr) {
		$fp = fopen ( $txtPath, 'w' );
		$flag = false;
		if (! $arr) {
			return false;
		}
		foreach ( $arr as $k ) {
			if (! $flag) {
				$flag = true;
				fwrite ( $fp, "@关键字词典,0,@\n" );
			}
			$strTemp = strtolower ( $k ['Keyword'] ) . ',' . $k ['CategoryId'] . "\n";
			fwrite ( $fp, $strTemp );
		}
		fclose ( $fp );
		return true;
	}
}
?>