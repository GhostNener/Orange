<?php
use Vendor\PHPMailer;
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
	$mail->SMTPDebug  = 0;
/* 	$mail->SMTPSecure = 'ssl'; */
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