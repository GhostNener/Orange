<?php
use Vendor\PHPMailer;

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