<?php
use Usercenter\Model\user_gradeModel;
use Vendor\PHPMailer;
use Usercenter\Model\userModel;
use Org\Util\String;
use Home\Model\noticeModel;
require_once './ORG/qiniu/qiniu.class.php';
/**
 * 检测用户是否激活
 *
 * @return boolean
 */
function isactivated() {
	$m = new userModel ();
	return $m->isactivated ();
}

/**
 * 创建系统通知（批量，单个）
 * 
 * @param array $arruid
 *        	收件人 可以是数组
 * @param unknown $title
 *        	通知标题
 * @param unknown $content
 *        	通知内容
 *        	
 */
function CSYSN($arruid, $title, $content) {
	$m = new noticeModel ();
	$m->CSYSN ( $arruid, $title, $content );
}

/**
 * 创建通知内容（CreateNoticeContent）
 *
 * @param array $data
 *        	：Title，GURL，UURL，Nick，Content，CId
 * @param string $tplpath
 *        	通知模板路径
 * @return string
 */
function CNC($data, $tplpath) {
	$karr = C ( 'MSG_TPL_PLACEHOLDER' );
	$c = file_get_contents ( $tplpath );
	foreach ( $karr as $k => $v ) {
		$c = str_replace ( $v, $data [$k], $c );
	}
	return htmlspecialchars ( $c );
}
/**
 * 获得文件路径（qiniu）
 *
 * @param string $fileName
 *        	文件名
 * @param string $type
 *        	20x20,40x40 ...
 * @return Ambigous <token, string>
 */
function getFileUrl($fileName, $type) {
	$m = new \qiniu ();
	return $m->GetFileUrl ( $fileName, $type );
}

/**
 * 检查文件是否存在
 *
 * @param string $path
 *        	路径
 * @param number $type
 *        	1，绝对路径
 * @return boolean
 */
function checkfile_exists($path, $type = 1) {
	$ex = substr ( strrchr ( $path, '.' ), 1 );
	if (! $path || ! $ex) {
		return false;
	}
	if ($type == 1) {
		$path = '.' . $path;
	}
	return file_exists ( $path );
}
/**
 * 获得默认图
 *
 * @param number $type
 *        	1：320_160，
 *        	2:800_300,
 *        	3:用户头像 20_20,
 *        	4:用户头像 40_40,
 *        	5:用户头像 150_150
 *        	6:用户通宵 80_80
 * @return string
 */
function getdefaultimg($type = 1) {
	switch ($type) {
		case 1 :
			$r = C ( 'DEFAULT_GOODS_IMG' );
			return $r ['G_320'];
		case 2 :
			$r = C ( 'DEFAULT_GOODS_IMG' );
			return $r ['G_830'];
		case 3 :
			$r = C ( 'DEFAULT_USER_AVATAR' );
			return $r ['A_20'];
		case 4 :
			$r = C ( 'DEFAULT_USER_AVATAR' );
			return $r ['A_40'];
		case 5 :
			$r = C ( 'DEFAULT_USER_AVATAR' );
			return $r ['A_150'];
		case 6 :
			$r = C ( 'DEFAULT_USER_AVATAR' );
			return $r ['A_80'];
	}
}
/**
 * 获取连续签到天数
 *
 * @param int $uid        	
 * @return number
 * @author NENER
 */
function getclockincount($uid = -1) {
	$m = new userModel ();
	return $m->getclockincount ( $uid );
}

/**
 * 上传文件
 *
 * @param array $config
 *        	上传配置
 * @return array status,msg
 * @author NENER
 */
function uploadfile($config, $file = null) {
	$upload = new \Think\Upload ( $config ); // 实例化上传类
	if (! $file) {
		$images = $upload->upload ();
	} else {
		$images = $upload->upload ( $file );
	}
	if (! $images) {
		return array (
				'status' => 0,
				'msg' => $upload->getError () 
		);
	} else {
		return array (
				'status' => 1,
				'msg' => $images 
		);
	}
}

/**
 * 标题分词
 *
 * @param string $title
 *        	标题
 * @param string $iscoding
 *        	是否进行编码
 * @param string $removerepeat
 *        	是否去重
 * @return array
 */
function searchpart($title, $iscoding = true, $removerepeat = false) {
	$str = strtolower ( $title );
	preg_match_all ( '/./u', $str, $okresult );
	return cutsingle ( $okresult [0], $iscoding, $removerepeat );
}
/**
 * 编码 去重
 *
 * @param array $arr
 *        	数据源（以为数组）
 * @param string $iscoding
 *        	是否编码
 * @param string $removerepeat
 *        	是否去重
 * @return array
 */
function cutsingle($arr, $iscoding = true, $removerepeat = false) {
	if ($removerepeat) {
		$arr = array_flip ( $arr );
		$arr = array_flip ( $arr );
	}
	if (! $iscoding) {
		return $arr;
	}
	foreach ( $arr as $k => $v ) {
		$v = trim ( $v );
		if (! preg_match ( "/^[\x7f-\xff]+$/", $v )) {
			$len = 1;
		} else {
			$len = 3;
			$arr [$k] = zhCode ( $v );
		}
	}
	return $arr;
}

/**
 * 生成一个相对唯一的key
 *
 * @param string $str
 *        	种子字符
 * @param number $rand1
 *        	随机码位数
 * @param number $randHF
 *        	前缀以及后缀随机码位数
 * @return string
 */
function createonekey($str = 'BigOranger', $rand1 = 6, $randHF = 6) {
	/* 生成key */
	$guid = uniqid ();
	$flag = randstr ( $rand1 );
	$key = sha1 ( $flag . $str . $guid . microtime ( true ), false );
	$key = randstr ( $randHF ) . $key . randstr ( $randHF );
	return $key;
}

/**
 * api专用 获取登录校验array
 *
 * @return array:_uid,_key
 */
function api_get_login_arr() {
	$arr = I ( 'get.' );
	if (! $arr ['_uid'] || ! $arr ['_key']) {
		$arr = I ( 'post.' );
	}
	if (! $arr ['_uid'] || ! $arr ['_key']) {
		$arr = file_get_contents ( "php://input" );
		$arr = json_decode ( $arr, true );
	}
	return $arr;
}
/**
 * 创建可用的交易方式
 *
 * @param int $id
 *        	交易id
 * @param number $type
 *        	1:返回关联数组 ，id，txt，msg 2：返回普通数组
 * @return array
 */
function createtradeway($id, $type = 1) {
	switch (( int ) $id) {
		case 1 :
			if ($type == 2) {
				$arr [] = $id;
			} else {
				$arr [] = array (
						'id' => $id,
						'txt' => '线上',
						'msg' => '用“金橘”作为货币交易,确定后“金橘”将被冻结, 直到确认收货后付款给卖家' 
				);
			}
			break;
		case 2 :
			if ($type == 2) {
				$arr [] = $id;
			} else {
				$arr [] = array (
						'id' => $id,
						'txt' => '线下',
						'msg' => '线下联系卖家, 按显示的价格进行交易' 
				);
			}
			break;
		case 3 :
			if ($type == 2) {
				$arr [] = 1;
				$arr [] = 2;
			} else {
				$arr [] = array (
						'id' => 1,
						'txt' => '线上',
						'msg' => '用“金橘”作为货币交易,确定后“金橘”将被冻结, 直到确认收货后付款给卖家' 
				);
				$arr [] = array (
						'id' => 2,
						'txt' => '线下',
						'msg' => '线下联系卖家, 按显示的价格进行交易' 
				);
			}
			break;
		default :
			$arr = null;
			break;
	}
	return $arr;
}

/**
 * 获取交易方式对应的文本
 *
 * @param int $wayid
 *        	id
 * @return string
 */
function gettradewaytxt($wayid) {
	if (! $wayid) {
		return '';
	}
	switch ($wayid) {
		case 1 :
			$rst = '线上';
			break;
		case 2 :
			$rst = '线下';
			break;
		case 3 :
			$rst = '线上/线下';
			break;
		default :
			$rst = '';
			break;
	}
	return $rst;
}

/**
 * api专用 ——获取uid
 *
 * @return int
 */
function api_get_uid() {
	$arr = api_get_login_arr ();
	return $arr ['_uid'];
}
/**
 * 公用（非API）——检测登录
 *
 * @param boolean $isadmin
 *        	是否是管理员 默认不是
 * @return boolean
 */
function isloin($isadmin = false) {
	$m = new userModel ();
	if (! $isadmin) {
		return ($m->islogin ( null, false, false ));
	} else {
		return ($m->islogin ( null, true, false ));
	}
}

/**
 * (公用)检查今天是否签到了
 *
 * @param unknown $uid        	
 * @return boolean true 签过了，false 没有
 */
function checkclockin($uid = -1) {
	$m = new userModel ();
	return ($m->checkclockin ( $uid ));
}

/**
 * 中文词转成GB18030编码
 *
 * @param String $str
 *        	源
 * @return String string 结果
 */
function zhCode($str) {
	if (! preg_match ( "/^[\x7f-\xff]+$/", $str )) {
		return $str;
	} else {
		$zhCode = '';
		$str = iconv ( 'UTF-8', 'GB18030', $str );
		for($i = 0; $i < strlen ( $str ) / 2; $i ++) {
			$word = substr ( $str, $i * 2, 2 );
			$zhCode .= sprintf ( "%02d%02d", ord ( $word [0] ) - 160, ord ( $word [1] ) - 160 );
		}
		return $zhCode;
	}
}

/**
 * 中文转拼音
 *
 * @param string $_String
 *        	源
 * @param string $_Code
 *        	源编码 默认UTF8
 * @return mixed
 */
function Pinyin($_String, $_Code = 'UTF8') { // GBK页面可改为gb2312，其他随意填写为UTF8
	$_Data = array (
			'a' => - 20319,
			'ai' => - 20317,
			'an' => - 20304,
			'ang' => - 20295,
			'ao' => - 20292,
			'ba' => - 20283,
			'bai' => - 20265,
			'ban' => - 20257,
			'bang' => - 20242,
			'bao' => - 20230,
			'bei' => - 20051,
			'ben' => - 20036,
			'beng' => - 20032,
			'bi' => - 20026,
			'bian' => - 20002,
			'biao' => - 19990,
			'bie' => - 19986,
			'bin' => - 19982,
			'bing' => - 19976,
			'bo' => - 19805,
			'bu' => - 19784,
			'ca' => - 19775,
			'cai' => - 19774,
			'can' => - 19763,
			'cang' => - 19756,
			'cao' => - 19751,
			'ce' => - 19746,
			'ceng' => - 19741,
			'cha' => - 19739,
			'chai' => - 19728,
			'chan' => - 19725,
			'chang' => - 19715,
			'chao' => - 19540,
			'che' => - 19531,
			'chen' => - 19525,
			'cheng' => - 19515,
			'chi' => - 19500,
			'chong' => - 19484,
			'chou' => - 19479,
			'chu' => - 19467,
			'chuai' => - 19289,
			'chuan' => - 19288,
			'chuang' => - 19281,
			'chui' => - 19275,
			'chun' => - 19270,
			'chuo' => - 19263,
			'ci' => - 19261,
			'cong' => - 19249,
			'cou' => - 19243,
			'cu' => - 19242,
			'cuan' => - 19238,
			'cui' => - 19235,
			'cun' => - 19227,
			'cuo' => - 19224,
			'da' => - 19218,
			'dai' => - 19212,
			'dan' => - 19038,
			'dang' => - 19023,
			'dao' => - 19018,
			'de' => - 19006,
			'deng' => - 19003,
			'di' => - 18996,
			'dian' => - 18977,
			'diao' => - 18961,
			'die' => - 18952,
			'ding' => - 18783,
			'diu' => - 18774,
			'dong' => - 18773,
			'dou' => - 18763,
			'du' => - 18756,
			'duan' => - 18741,
			'dui' => - 18735,
			'dun' => - 18731,
			'duo' => - 18722,
			'e' => - 18710,
			'en' => - 18697,
			'er' => - 18696,
			'fa' => - 18526,
			'fan' => - 18518,
			'fang' => - 18501,
			'fei' => - 18490,
			'fen' => - 18478,
			'feng' => - 18463,
			'fo' => - 18448,
			'fou' => - 18447,
			'fu' => - 18446,
			'ga' => - 18239,
			'gai' => - 18237,
			'gan' => - 18231,
			'gang' => - 18220,
			'gao' => - 18211,
			'ge' => - 18201,
			'gei' => - 18184,
			'gen' => - 18183,
			'geng' => - 18181,
			'gong' => - 18012,
			'gou' => - 17997,
			'gu' => - 17988,
			'gua' => - 17970,
			'guai' => - 17964,
			'guan' => - 17961,
			'guang' => - 17950,
			'gui' => - 17947,
			'gun' => - 17931,
			'guo' => - 17928,
			'ha' => - 17922,
			'hai' => - 17759,
			'han' => - 17752,
			'hang' => - 17733,
			'hao' => - 17730,
			'he' => - 17721,
			'hei' => - 17703,
			'hen' => - 17701,
			'heng' => - 17697,
			'hong' => - 17692,
			'hou' => - 17683,
			'hu' => - 17676,
			'hua' => - 17496,
			'huai' => - 17487,
			'huan' => - 17482,
			'huang' => - 17468,
			'hui' => - 17454,
			'hun' => - 17433,
			'huo' => - 17427,
			'ji' => - 17417,
			'jia' => - 17202,
			'jian' => - 17185,
			'jiang' => - 16983,
			'jiao' => - 16970,
			'jie' => - 16942,
			'jin' => - 16915,
			'jing' => - 16733,
			'jiong' => - 16708,
			'jiu' => - 16706,
			'ju' => - 16689,
			'juan' => - 16664,
			'jue' => - 16657,
			'jun' => - 16647,
			'ka' => - 16474,
			'kai' => - 16470,
			'kan' => - 16465,
			'kang' => - 16459,
			'kao' => - 16452,
			'ke' => - 16448,
			'ken' => - 16433,
			'keng' => - 16429,
			'kong' => - 16427,
			'kou' => - 16423,
			'ku' => - 16419,
			'kua' => - 16412,
			'kuai' => - 16407,
			'kuan' => - 16403,
			'kuang' => - 16401,
			'kui' => - 16393,
			'kun' => - 16220,
			'kuo' => - 16216,
			'la' => - 16212,
			'lai' => - 16205,
			'lan' => - 16202,
			'lang' => - 16187,
			'lao' => - 16180,
			'le' => - 16171,
			'lei' => - 16169,
			'leng' => - 16158,
			'li' => - 16155,
			'lia' => - 15959,
			'lian' => - 15958,
			'liang' => - 15944,
			'liao' => - 15933,
			'lie' => - 15920,
			'lin' => - 15915,
			'ling' => - 15903,
			'liu' => - 15889,
			'long' => - 15878,
			'lou' => - 15707,
			'lu' => - 15701,
			'lv' => - 15681,
			'luan' => - 15667,
			'lue' => - 15661,
			'lun' => - 15659,
			'luo' => - 15652,
			'ma' => - 15640,
			'mai' => - 15631,
			'man' => - 15625,
			'mang' => - 15454,
			'mao' => - 15448,
			'me' => - 15436,
			'mei' => - 15435,
			'men' => - 15419,
			'meng' => - 15416,
			'mi' => - 15408,
			'mian' => - 15394,
			'miao' => - 15385,
			'mie' => - 15377,
			'min' => - 15375,
			'ming' => - 15369,
			'miu' => - 15363,
			'mo' => - 15362,
			'mou' => - 15183,
			'mu' => - 15180,
			'na' => - 15165,
			'nai' => - 15158,
			'nan' => - 15153,
			'nang' => - 15150,
			'nao' => - 15149,
			'ne' => - 15144,
			'nei' => - 15143,
			'nen' => - 15141,
			'neng' => - 15140,
			'ni' => - 15139,
			'nian' => - 15128,
			'niang' => - 15121,
			'niao' => - 15119,
			'nie' => - 15117,
			'nin' => - 15110,
			'ning' => - 15109,
			'niu' => - 14941,
			'nong' => - 14937,
			'nu' => - 14933,
			'nv' => - 14930,
			'nuan' => - 14929,
			'nue' => - 14928,
			'nuo' => - 14926,
			'o' => - 14922,
			'ou' => - 14921,
			'pa' => - 14914,
			'pai' => - 14908,
			'pan' => - 14902,
			'pang' => - 14894,
			'pao' => - 14889,
			'pei' => - 14882,
			'pen' => - 14873,
			'peng' => - 14871,
			'pi' => - 14857,
			'pian' => - 14678,
			'piao' => - 14674,
			'pie' => - 14670,
			'pin' => - 14668,
			'ping' => - 14663,
			'po' => - 14654,
			'pu' => - 14645,
			'qi' => - 14630,
			'qia' => - 14594,
			'qian' => - 14429,
			'qiang' => - 14407,
			'qiao' => - 14399,
			'qie' => - 14384,
			'qin' => - 14379,
			'qing' => - 14368,
			'qiong' => - 14355,
			'qiu' => - 14353,
			'qu' => - 14345,
			'quan' => - 14170,
			'que' => - 14159,
			'qun' => - 14151,
			'ran' => - 14149,
			'rang' => - 14145,
			'rao' => - 14140,
			're' => - 14137,
			'ren' => - 14135,
			'reng' => - 14125,
			'ri' => - 14123,
			'rong' => - 14122,
			'rou' => - 14112,
			'ru' => - 14109,
			'ruan' => - 14099,
			'rui' => - 14097,
			'run' => - 14094,
			'ruo' => - 14092,
			'sa' => - 14090,
			'sai' => - 14087,
			'san' => - 14083,
			'sang' => - 13917,
			'sao' => - 13914,
			'se' => - 13910,
			'sen' => - 13907,
			'seng' => - 13906,
			'sha' => - 13905,
			'shai' => - 13896,
			'shan' => - 13894,
			'shang' => - 13878,
			'shao' => - 13870,
			'she' => - 13859,
			'shen' => - 13847,
			'sheng' => - 13831,
			'shi' => - 13658,
			'shou' => - 13611,
			'shu' => - 13601,
			'shua' => - 13406,
			'shuai' => - 13404,
			'shuan' => - 13400,
			'shuang' => - 13398,
			'shui' => - 13395,
			'shun' => - 13391,
			'shuo' => - 13387,
			'si' => - 13383,
			'song' => - 13367,
			'sou' => - 13359,
			'su' => - 13356,
			'suan' => - 13343,
			'sui' => - 13340,
			'sun' => - 13329,
			'suo' => - 13326,
			'ta' => - 13318,
			'tai' => - 13147,
			'tan' => - 13138,
			'tang' => - 13120,
			'tao' => - 13107,
			'te' => - 13096,
			'teng' => - 13095,
			'ti' => - 13091,
			'tian' => - 13076,
			'tiao' => - 13068,
			'tie' => - 13063,
			'ting' => - 13060,
			'tong' => - 12888,
			'tou' => - 12875,
			'tu' => - 12871,
			'tuan' => - 12860,
			'tui' => - 12858,
			'tun' => - 12852,
			'tuo' => - 12849,
			'wa' => - 12838,
			'wai' => - 12831,
			'wan' => - 12829,
			'wang' => - 12812,
			'wei' => - 12802,
			'wen' => - 12607,
			'weng' => - 12597,
			'wo' => - 12594,
			'wu' => - 12585,
			'xi' => - 12556,
			'xia' => - 12359,
			'xian' => - 12346,
			'xiang' => - 12320,
			'xiao' => - 12300,
			'xie' => - 12120,
			'xin' => - 12099,
			'xing' => - 12089,
			'xiong' => - 12074,
			'xiu' => - 12067,
			'xu' => - 12058,
			'xuan' => - 12039,
			'xue' => - 11867,
			'xun' => - 11861,
			'ya' => - 11847,
			'yan' => - 11831,
			'yang' => - 11798,
			'yao' => - 11781,
			'ye' => - 11604,
			'yi' => - 11589,
			'yin' => - 11536,
			'ying' => - 11358,
			'yo' => - 11340,
			'yong' => - 11339,
			'you' => - 11324,
			'yu' => - 11303,
			'yuan' => - 11097,
			'yue' => - 11077,
			'yun' => - 11067,
			'za' => - 11055,
			'zai' => - 11052,
			'zan' => - 11045,
			'zang' => - 11041,
			'zao' => - 11038,
			'ze' => - 11024,
			'zei' => - 11020,
			'zen' => - 11019,
			'zeng' => - 11018,
			'zha' => - 11014,
			'zhai' => - 10838,
			'zhan' => - 10832,
			'zhang' => - 10815,
			'zhao' => - 10800,
			'zhe' => - 10790,
			'zhen' => - 10780,
			'zheng' => - 10764,
			'zhi' => - 10587,
			'zhong' => - 10544,
			'zhou' => - 10533,
			'zhu' => - 10519,
			'zhua' => - 10331,
			'zhuai' => - 10329,
			'zhuan' => - 10328,
			'zhuang' => - 10322,
			'zhui' => - 10315,
			'zhun' => - 10309,
			'zhuo' => - 10307,
			'zi' => - 10296,
			'zong' => - 10281,
			'zou' => - 10274,
			'zu' => - 10270,
			'zuan' => - 10262,
			'zui' => - 10260,
			'zun' => - 10256,
			'zuo' => - 10254 
	); // array_combine ( $_TDataKey, $_TDataValue );
	arsort ( $_Data );
	reset ( $_Data );
	if ($_Code != 'gb2312')
		$_String = _U2_Utf8_Gb ( $_String );
	$_Res = '';
	for($i = 0; $i < strlen ( $_String ); $i ++) {
		$_P = ord ( substr ( $_String, $i, 1 ) );
		if ($_P > 160) {
			$_Q = ord ( substr ( $_String, ++ $i, 1 ) );
			$_P = $_P * 256 + $_Q - 65536;
		}
		$_Res .= _Pinyin ( $_P, $_Data );
	}
	return preg_replace ( "/[^a-z0-9]*/", '', $_Res );
}
function _Pinyin($_Num, $_Data) {
	if ($_Num > 0 && $_Num < 160) {
		return chr ( $_Num );
	} elseif ($_Num < - 20319 || $_Num > - 10247) {
		return '';
	} else {
		foreach ( $_Data as $k => $v ) {
			if ($v <= $_Num)
				break;
		}
		return $k;
	}
}
function _U2_Utf8_Gb($_C) {
	$_String = '';
	if ($_C < 0x80) {
		$_String .= $_C;
	} elseif ($_C < 0x800) {
		$_String .= chr ( 0xC0 | $_C >> 6 );
		$_String .= chr ( 0x80 | $_C & 0x3F );
	} elseif ($_C < 0x10000) {
		$_String .= chr ( 0xE0 | $_C >> 12 );
		$_String .= chr ( 0x80 | $_C >> 6 & 0x3F );
		$_String .= chr ( 0x80 | $_C & 0x3F );
	} elseif ($_C < 0x200000) {
		$_String .= chr ( 0xF0 | $_C >> 18 );
		$_String .= chr ( 0x80 | $_C >> 12 & 0x3F );
		$_String .= chr ( 0x80 | $_C >> 6 & 0x3F );
		$_String .= chr ( 0x80 | $_C & 0x3F );
	}
	return iconv ( 'UTF-8', 'GB2312', $_String );
}

/**
 * 压缩图片
 *
 * @author NENER
 * @param string $url:
 *        	原始图片的路径
 * @param string $imgname:
 *        	保存的文件名【不是路径，包含文件后缀的文件名】
 * @return array:压缩图路径 从大到小
 */
function getallthumb($url, $imgname) {
	/* 获取后缀 */
	$ext = substr ( strrchr ( $imgname, '.' ), 1 );
	/* 替换后缀 */
	$imgname = str_replace ( '.' . $ext, '.jpg', $imgname );
	$parr = C ( 'GOODS_IMG_PATH' );
	$rooturl = '.';
	/* 小图路径320*160 */
	$url_1 = $rooturl . $parr ['G_320'] . $imgname;
	/* 大图路径 800*800 */
	$url_8 = $rooturl . $parr ['G_880'] . $imgname;
	/* 800*300 */
	$url_3 = $rooturl . $parr ['G_830'] . $imgname;
	$sizearr = C ( 'GOODS_IMG_SIZE' );
	/* 获取小图配置 */
	$arr_1 = $sizearr ['G_320'];
	/* 获取大图配置 */
	$arr_8 = $sizearr ['G_880'];
	$arr_3 = $sizearr ['G_830'];
	$imgobj = cutimg ( $url, $url_8, $arr_8, 1 );
	if (! $imgobj) {
		return false;
	}
	if (! cutimg ( $url_8, $url_3, $arr_3, 2 )) {
		return false;
	}
	if (! cutimg ( $url_3, $url_1, $arr_1, 2 )) {
		return false;
	}
	/* 删除源图 */
	unlink ( $url );
	$ex = substr ( strrchr ( $imgname, '.' ), 0 );
	$filename = str_replace ( $ex, '.' . C ( 'IMG_SAVE_TYPE' ), $imgname );
	return $filename;
}

/**
 * 旋转图片
 *
 * @param string $filename
 *        	原始路径
 * @param string $savesrc
 *        	保存路径
 * @param string $degrees
 *        	旋转度数
 * @return boolean
 */
function rotateimg($filename, $savesrc, $degrees = 90) {
	// 读取图片
	$data = @getimagesize ( $filename );
	if ($data == false)
		return false;
		// 读取格式
	switch ($data [2]) {
		case 1 :
			$src_f = imagecreatefromgif ( $filename );
			break;
		case 2 :
			$src_f = imagecreatefromjpeg ( $filename );
			break;
		case 3 :
			$src_f = imagecreatefrompng ( $filename );
			break;
		default :
			$src_f = null;
			break;
	}
	if (! $src_f) {
		return false;
	}
	$rotate = @imagerotate ( $src_f, $degrees, 0 );
	if (! imagejpeg ( $rotate, $savesrc, 100 )) {
		return false;
	}
	@imagedestroy ( $rotate );
	return true;
}
/**
 * 获得exift 然后旋转
 *
 * @param string $filename
 *        	图片路径
 */
function getexif($filename) {
	$exif = exif_read_data ( $filename, 0, true );
	$r = $exif ['IFD0'] ['Orientation'];
	$degrees = 0;
	if (! $r) {
		return true;
	}
	switch ($r) {
		case 3 :
			$degrees = 180;
			break;
		case 6 :
			$degrees = - 90;
			break;
		case 8 :
			$degrees = 90;
			break;
		default :
			$degrees = 0;
			break;
	}
	if (! $degrees) {
		return 1;
	}
	rotateimg ( $filename, $filename, $degrees );
}

/**
 * 裁剪图片
 *
 * @param string $openurl
 *        	：图片路径
 * @param string $saveurl：保存路径        	
 *
 * @param array $size：宽
 *        	，高
 * @param number $type：裁剪类型：1：等比缩放，2：居中固定尺寸裁剪        	
 */
function cutimg($openurl, $saveurl, $size, $type = 1) {
	getexif ( $openurl );
	$imagedal = new \Think\Image ();
	$imagedal->open ( $openurl );
	if ($type == 1) {
		$obj = $imagedal->thumb ( ( int ) $size [0], ( int ) $size [1], \Think\Image::IMAGE_THUMB_SCALE )->save ( $saveurl, C ( 'IMG_SAVE_TYPE' ), C ( 'IMG_SAVE_QUALITY' ), true );
	} else {
		$obj = $imagedal->thumb ( ( int ) $size [0], ( int ) $size [1], \Think\Image::IMAGE_THUMB_CENTER )->save ( $saveurl, C ( 'IMG_SAVE_TYPE' ), C ( 'IMG_SAVE_QUALITY' ), true );
	}
	return $obj;
}
/**
 * 检查是否为空
 *
 * @param obj $v        	
 * @return boolean
 */
function checknull($v) {
	if (! $v) {
		return false;
	} else {
		return true;
	}
}
/**
 * 检查是不是QQ
 *
 * @param int $qq        	
 * @return boolean
 */
function checkqq($qq) {
	$isQQ = "/^[1-9]{1}[0-9]{4,9}$/";
	preg_match ( $isQQ, $qq, $result );
	if (! $result) {
		return false;
	}
	return true;
}

/**
 * 检查手机是否合法
 *
 * @param int $tel        	
 * @return boolean
 */
function checktel($tel) {
	if (! tel) {
		return false;
	}
	$isMobile = "/^(?:13\d|14\d|15\d|18\d)\d{5}(\d{3}|\*{3})$/";
	preg_match ( $isMobile, $tel, $result );
	if (! $result) {
		return false;
	}
	return true;
}
/**
 * 验证密码是否合法
 *
 * @param int $Password        	
 * @return boolean
 */
function checkpwd($Password) {
	$ispwd = "/^[a-z0-9_A-Z~!@#$%^&*]{6,18}$/"; // "/^(?!\D+$)(?!\d+$)[a-zA-Z0-9_]\w{6,18}$/";
	preg_match ( $ispwd, $Password, $result );
	if (! $result) {
		return false;
	}
	return true;
}
/**
 * 检查邮件
 *
 * @param int $Name        	
 * @return boolean
 */
function checkmail($mial) {
	if (! $mial) {
		return false;
	}
	$ismail1 = "/^([a-z0-9_\.-]+)@([\da-z\.-]+)\.([a-z\.]{2,6})$/";
	$ismail2 = "/^[a-z\d]+(\.[a-z\d]+)*@([\da-z](-[\da-z])?)+(\.{1,2}[a-z]+)+$/";
	preg_match ( $ismail1, $mial, $result1 );
	preg_match ( $ismail1, $mial, $result2 );
	if (! $result1 && ! $result1) {
		return false;
	}
	return true;
}

/**
 * 生成指定位数的随机字符串
 *
 * @author NENER
 * @param int $lenth
 *        	：需要生成的长度
 * @return string:结果
 */
function randstr($length = 8) {
	if ($length <= 0) {
		return null;
	}
	$pattern = '1234567890abcdefghijklmnopqrstuvwxyz_ABCDEFGHIJKLOMNOPQRSTUVWXYZ'; // 字符池
	$end = strlen ( $pattern ) - 1;
	$rst = '';
	for($i = 0; $i < $length; $i ++) {
		$rst .= $pattern {mt_rand ( 0, $end )}; // 生成php随机数
	}
	return $rst;
}
/**
 * 发送邮件
 *
 * @param string $subject
 *        	主题
 * @param string $content
 *        	内容
 * @param string $email
 *        	收件人
 * @return boolean
 */
function sendEmail($subject, $content, $email) {
	$mail = new PHPMailer ();
	$config = C ( 'ORANGER_MAIL' );
	$body = $content;
	$mail->IsSMTP ();
	try {
		$mail->SMTPDebug = 0;
		/* $mail->SMTPSecure = 'ssl'; */
		$mail->SMTPAuth = true; // enable SMTP authentication
		$mail->SMTPKeepAlive = true; // sets the prefix to the servier
		$mail->CharSet = "utf-8";
		$mail->Host = $config ['SMTP_HOST'];
		$mail->Port = $config ['SMTP_PORT'];
		$mail->Username = $config ['SMTP_USER'];
		$mail->Password = $config ['SMTP_PASS'];
		$mail->From = $config ['FROM_EMAIL'];
		$mail->FromName = $config ['FROM_NAME'];
		$mail->Subject = $subject;
		$mail->AltBody = $body;
		$mail->WordWrap = 50; // set word wrap
		$mail->MsgHTML ( $body );
		$mail->AddReplyTo ( $config ['REPLY_EMAIL'], $config ['REPLY_NAME'] );
		// $mail->AddAttachment("attachment.jpg"); // 附件1
		// $mail->AddAttachment("attachment.zip"); // 附件2
		$mail->AddAddress ( $email, $email ); // 接收邮件的账号
		$mail->IsHTML ( true ); // send as HTML
		$rst = $mail->Send ();
		return $rst;
	} catch ( Exception $e ) {
		return false;
	}
}
/**
 * 帐号激活邮件
 *
 * @param string $usermail
 *        	用户邮箱
 * @param string $url
 *        	激活URL
 * @return boolean
 */
function send_activate_mail($usermail, $url) {
	return sendEmail ( '帐号激活', $url, $usermail );
}

/**
 * 计算等级
 *
 * @param
 *        	EXP
 *        	经验
 * @return Title 等级名称
 */
function getgrade($EXP, $type = 1) {
	$whereArr ['MinEXP'] = array (
			'elt',
			$EXP 
	);
	$whereArr ['MaxEXP'] = array (
			'egt',
			$EXP 
	);
	$whereArr ['Status'] = 10;
	$model = new user_gradeModel ();
	$rst = $model->getgrade ( ( int ) $EXP );
	if ($type == 1) {
		return $rst ['Title'];
	} else {
		return $rst ['Number'];
	}
}

/**
 * 删除文件（七牛）
 *
 * @param unknown $key        	
 * @return multitype:number string
 */
function qiniuDelFile($key) {
	$qiniu = new \qiniu ();
	return $qiniu->delFile ( $key );
}
/**
 * 获得token
 *
 * @param unknown $action        	
 * @return Ambigous <token, string>
 */
function qiniuGetToken($action) {
	$qiniu = new \qiniu ();
	return $qiniu->GetToken ( $action );
}

?>