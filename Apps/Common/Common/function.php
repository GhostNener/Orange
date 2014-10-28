<?php
use Vendor\PHPMailer;
function Pinyin($_String, $_Code = 'UTF8') { // GBK页面可改为gb2312，其他随意填写为UTF8
	$_DataKey = "a|ai|an|ang|ao|ba|bai|ban|bang|bao|bei|ben|beng|bi|bian|biao|bie|bin|bing|bo|bu|ca|cai|can|cang|cao|ce|ceng|cha" . "|chai|chan|chang|chao|che|chen|cheng|chi|chong|chou|chu|chuai|chuan|chuang|chui|chun|chuo|ci|cong|cou|cu|" . "cuan|cui|cun|cuo|da|dai|dan|dang|dao|de|deng|di|dian|diao|die|ding|diu|dong|dou|du|duan|dui|dun|duo|e|en|er" . "|fa|fan|fang|fei|fen|feng|fo|fou|fu|ga|gai|gan|gang|gao|ge|gei|gen|geng|gong|gou|gu|gua|guai|guan|guang|gui" . "|gun|guo|ha|hai|han|hang|hao|he|hei|hen|heng|hong|hou|hu|hua|huai|huan|huang|hui|hun|huo|ji|jia|jian|jiang" . "|jiao|jie|jin|jing|jiong|jiu|ju|juan|jue|jun|ka|kai|kan|kang|kao|ke|ken|keng|kong|kou|ku|kua|kuai|kuan|kuang" . "|kui|kun|kuo|la|lai|lan|lang|lao|le|lei|leng|li|lia|lian|liang|liao|lie|lin|ling|liu|long|lou|lu|lv|luan|lue" . "|lun|luo|ma|mai|man|mang|mao|me|mei|men|meng|mi|mian|miao|mie|min|ming|miu|mo|mou|mu|na|nai|nan|nang|nao|ne" . "|nei|nen|neng|ni|nian|niang|niao|nie|nin|ning|niu|nong|nu|nv|nuan|nue|nuo|o|ou|pa|pai|pan|pang|pao|pei|pen" . "|peng|pi|pian|piao|pie|pin|ping|po|pu|qi|qia|qian|qiang|qiao|qie|qin|qing|qiong|qiu|qu|quan|que|qun|ran|rang" . "|rao|re|ren|reng|ri|rong|rou|ru|ruan|rui|run|ruo|sa|sai|san|sang|sao|se|sen|seng|sha|shai|shan|shang|shao|" . "she|shen|sheng|shi|shou|shu|shua|shuai|shuan|shuang|shui|shun|shuo|si|song|sou|su|suan|sui|sun|suo|ta|tai|" . "tan|tang|tao|te|teng|ti|tian|tiao|tie|ting|tong|tou|tu|tuan|tui|tun|tuo|wa|wai|wan|wang|wei|wen|weng|wo|wu" . "|xi|xia|xian|xiang|xiao|xie|xin|xing|xiong|xiu|xu|xuan|xue|xun|ya|yan|yang|yao|ye|yi|yin|ying|yo|yong|you" . "|yu|yuan|yue|yun|za|zai|zan|zang|zao|ze|zei|zen|zeng|zha|zhai|zhan|zhang|zhao|zhe|zhen|zheng|zhi|zhong|" . "zhou|zhu|zhua|zhuai|zhuan|zhuang|zhui|zhun|zhuo|zi|zong|zou|zu|zuan|zui|zun|zuo";
	$_DataValue = "-20319|-20317|-20304|-20295|-20292|-20283|-20265|-20257|-20242|-20230|-20051|-20036|-20032|-20026|-20002|-19990" . "|-19986|-19982|-19976|-19805|-19784|-19775|-19774|-19763|-19756|-19751|-19746|-19741|-19739|-19728|-19725" . "|-19715|-19540|-19531|-19525|-19515|-19500|-19484|-19479|-19467|-19289|-19288|-19281|-19275|-19270|-19263" . "|-19261|-19249|-19243|-19242|-19238|-19235|-19227|-19224|-19218|-19212|-19038|-19023|-19018|-19006|-19003" . "|-18996|-18977|-18961|-18952|-18783|-18774|-18773|-18763|-18756|-18741|-18735|-18731|-18722|-18710|-18697" . "|-18696|-18526|-18518|-18501|-18490|-18478|-18463|-18448|-18447|-18446|-18239|-18237|-18231|-18220|-18211" . "|-18201|-18184|-18183|-18181|-18012|-17997|-17988|-17970|-17964|-17961|-17950|-17947|-17931|-17928|-17922" . "|-17759|-17752|-17733|-17730|-17721|-17703|-17701|-17697|-17692|-17683|-17676|-17496|-17487|-17482|-17468" . "|-17454|-17433|-17427|-17417|-17202|-17185|-16983|-16970|-16942|-16915|-16733|-16708|-16706|-16689|-16664" . "|-16657|-16647|-16474|-16470|-16465|-16459|-16452|-16448|-16433|-16429|-16427|-16423|-16419|-16412|-16407" . "|-16403|-16401|-16393|-16220|-16216|-16212|-16205|-16202|-16187|-16180|-16171|-16169|-16158|-16155|-15959" . "|-15958|-15944|-15933|-15920|-15915|-15903|-15889|-15878|-15707|-15701|-15681|-15667|-15661|-15659|-15652" . "|-15640|-15631|-15625|-15454|-15448|-15436|-15435|-15419|-15416|-15408|-15394|-15385|-15377|-15375|-15369" . "|-15363|-15362|-15183|-15180|-15165|-15158|-15153|-15150|-15149|-15144|-15143|-15141|-15140|-15139|-15128" . "|-15121|-15119|-15117|-15110|-15109|-14941|-14937|-14933|-14930|-14929|-14928|-14926|-14922|-14921|-14914" . "|-14908|-14902|-14894|-14889|-14882|-14873|-14871|-14857|-14678|-14674|-14670|-14668|-14663|-14654|-14645" . "|-14630|-14594|-14429|-14407|-14399|-14384|-14379|-14368|-14355|-14353|-14345|-14170|-14159|-14151|-14149" . "|-14145|-14140|-14137|-14135|-14125|-14123|-14122|-14112|-14109|-14099|-14097|-14094|-14092|-14090|-14087" . "|-14083|-13917|-13914|-13910|-13907|-13906|-13905|-13896|-13894|-13878|-13870|-13859|-13847|-13831|-13658" . "|-13611|-13601|-13406|-13404|-13400|-13398|-13395|-13391|-13387|-13383|-13367|-13359|-13356|-13343|-13340" . "|-13329|-13326|-13318|-13147|-13138|-13120|-13107|-13096|-13095|-13091|-13076|-13068|-13063|-13060|-12888" . "|-12875|-12871|-12860|-12858|-12852|-12849|-12838|-12831|-12829|-12812|-12802|-12607|-12597|-12594|-12585" . "|-12556|-12359|-12346|-12320|-12300|-12120|-12099|-12089|-12074|-12067|-12058|-12039|-11867|-11861|-11847" . "|-11831|-11798|-11781|-11604|-11589|-11536|-11358|-11340|-11339|-11324|-11303|-11097|-11077|-11067|-11055" . "|-11052|-11045|-11041|-11038|-11024|-11020|-11019|-11018|-11014|-10838|-10832|-10815|-10800|-10790|-10780" . "|-10764|-10587|-10544|-10533|-10519|-10331|-10329|-10328|-10322|-10315|-10309|-10307|-10296|-10281|-10274" . "|-10270|-10262|-10260|-10256|-10254";
	$_TDataKey = explode ( '|', $_DataKey );
	$_TDataValue = explode ( '|', $_DataValue );
	$_Data = array_combine ( $_TDataKey, $_TDataValue );
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
 *        	保存的文件名【需要包含文件后缀】
 * @return array:压缩图路径 第一个参数是 大图 第二个是缩略图
 */
function getallthumb($url, $imgname) {
	/* 获取后缀 */
	$ext = substr ( strrchr ( $imgname, '.' ), 1 );
	/* 替换后缀 */
	$imgname = str_replace ( '.' . $ext, '.jpg', $imgname );
	$rooturl = C ( 'GOODS_IMG_ROOT' );
	/* 小图路径 */
	$url_1 = $rooturl . C ( 'GOODS_IMG_100' ) . $imgname;
	/* 大图路径 */
	$url_8 = $rooturl . C ( 'GOODS_IMG_800' ) . $imgname;
	$imagedal = new \Think\Image ();
	$imagedal->open ( $url );
	// $size = $imagedal->size ();
	/* 获取小图配置 */
	$arrthumb = C ( 'GOODS_IMG_THUMB' );
	/* 获取大图配置 */
	$arrmd = C ( 'GOODS_IMG_MD' );
	$imagedal->thumb ( ( int ) $arrmd [0], ( int ) $arrmd [1] )->save ( $url_8, C ( 'IMG_SAVE_TYPE' ), C ( 'IMG_SAVE_QUALITY' ), true );
	$imagedal->thumb ( ( int ) $arrthumb [0], ( int ) $arrthumb [1], \Think\Image::IMAGE_THUMB_CENTER )->save ( $url_1, C ( 'IMG_SAVE_TYPE' ), C ( 'IMG_SAVE_QUALITY' ), true );
	return array (
			$url_8,
			$url_1 
	);
}

/**
 * 检查是否为空
 *
 * @param unknown $v        	
 * @return boolean
 */
function checknull($v) {
	if (! $v) {
		return false;
	} else {
		return true;
	}
}
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
 * @param unknown $tel        	
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
 * @param unknown $Password        	
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
 * @param unknown $Name        	
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
	$pattern = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLOMNOPQRSTUVWXYZ_'; // 字符池
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
	return ($mail->Send ());
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
?>