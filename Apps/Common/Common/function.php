<?php

/**
 * 检查用户是否登录
 * @author NENER  */
function is_login() {
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
	$pattern = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLOMNOPQRSTUVWXYZ/_+'; // 字符池
	$end = strlen ( $pattern ) - 1;
	$rst='';
	for($i = 0; $i < $length; $i ++) {
		$rst .= $pattern {mt_rand ( 0, $end )}; // 生成php随机数
	}
	return $rst;
}
?>