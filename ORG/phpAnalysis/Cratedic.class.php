<?php
ini_set ( 'memory_limit', '128M' );
require_once './ORG/phpAnalysis/phpanalysis.class.php';
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
	public $searchdic = 'dict/search_dic_full.dic';
	/* 全文检索基本词典txt路径 */
	public $basetxt = '/dict/txt/base_dic_full.txt';
	/* 缓存txt */
	private $temptxt = '/dict/txt/temp_dic_full.txt';
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
		$basetxt = dirname ( __FILE__ ) . $this->basetxt;
		$temptxt = dirname ( __FILE__ ) . $this->temptxt;
		/* 生成关键字词典 */
		PhpAnalysis::$loadInit = false;
		$pa = new PhpAnalysis ( 'utf-8', 'utf-8', false, '', $this->categorydic );
		$pa->MakeDict ( $txtpath );
		/* 生成全文检索词典 */
		$this->jointtxt ( $txtpath, $basetxt, $temptxt );
		$pa = new PhpAnalysis ( 'utf-8', 'utf-8', false, '', $this->searchdic );
		$pa->MakeDict ( $temptxt );
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
		$fp = fopen ( $txtPath, 'w+' );
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
	/**
	 * 拼接文件 生成新的文件
	 *
	 * @param unknown $sourcespatha
	 *        	：源文件绝对路径
	 * @param unknown $sourcespathb
	 *        	：：源文件绝对路径
	 * @param unknown $savepath
	 *        	：要保存的绝对路径
	 */
	private function jointtxt($sourcespatha, $sourcespathb, $savepath) {
		$strbase = file_get_contents ( $sourcespatha );
		$newdic = file_get_contents ( $sourcespathb );
		$newdic = $newdic . "\n" . $strbase;
		$fp = fopen ( $savepath, 'w+' );
		fwrite ( $fp, $newdic );
		fclose ( $fp );
	}
}
?>