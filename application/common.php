<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件 · 版本1

/**
 * 匹配星座
 * @Author   Kevin
 * @DateTime 2020-10-19T10:56:45+0800
 * @param    date						$date [生日]
 */
function mateStarZuo($date){
	$stamp = strtotime($date);
	$v = date("Ymd", $stamp);
	$y = date("Y", $stamp);
	switch ($v) {
		case $y.'0120'<=$v && $v<=$y.'0218':
			return [
				'id' => 11,
				'title' => '水瓶座',
				'start' => '1.20',
				'end'   => '2.18'
			];
			break;
		case $y.'0219'<=$v && $v<=$y.'0320':
			return [
				'id' => 12,
				'title' => '双鱼座',
				'start' => '2.19',
				'end'   => '3.20'
			];
			break;
		case $y.'0321'<=$v && $v<=$y.'0419':
			return [
				'id' => 1,
				'title' => '白羊座',
				'start' => '3.21',
				'end'   => '4.19'
			];
			break;
		case $y.'0420'<=$v && $v<=$y.'0520':
			return [
				'id' => 2,
				'title' => '金牛座',
				'start' => '4.20',
				'end'   => '5.20'
			];
			break;
		case $y.'0521'<=$v && $v<=$y.'0621':
			return [
				'id' => 3,
				'title' => '双子座',
				'start' => '5.21',
				'end'   => '6.21'
			];
			break;
		case $y.'0624'<=$v && $v<=$y.'0722':
			return [
				'id' => 4,
				'title' => '巨蟹座',
				'start' => '6.24',
				'end'   => '7.22'
			];
			break;
		case $y.'0723'<=$v && $v<=$y.'0822':
			return [
				'id' => 5,
				'title' => '狮子座',
				'start' => '7.23',
				'end'   => '8.22'
			];
			break;
		case $y.'0823'<=$v && $v<=$y.'0922':
			return [
				'id' => 6,
				'title' => '处女座',
				'start' => '8.23',
				'end'   => '9.22'
			];
			break;
		case $y.'0923'<=$v && $v<=$y.'1023':
			return [
				'id' => 7,
				'title' => '天秤座',
				'start' => '9.23',
				'end'   => '10.23'
			];
			break;
		case $y.'1024'<=$v && $v<=$y.'1122':
			return [
				'id' => 8,
				'title' => '天蝎座',
				'start' => '10.24',
				'end'   => '11.22'
			];
			break;
		case $y.'1123'<=$v && $v<=$y.'1221':
			return [
				'id' => 9,
				'title' => '射手座',
				'start' => '11.23',
				'end'   => '12.21'
			];
			break;
		default:
			return [
				'id' => 10,
				'title' => '摩羯座',
				'start' => '12.22',
				'end'   => '1.19'
			];
			break;
	}
}

/**
 * 获取星座
 * @Author   Kevin
 * @DateTime 2020-10-20T11:51:46+0800
 * @param    [type]                   $date [description]
 * @return   [type]                         [description]
 */
function getStarZuo(){
	return [
		'0' => '',
		'1' => '白羊座',
		'2' => '金牛座',
		'3' => '双子座',
		'4' => '巨蟹座',
		'5' => '狮子座',
		'6' => '处女座',
		'7' => '天秤座',
		'8' => '天蝎座',
		'9' => '射手座',
		'10' => '摩羯座',
		'11' => '水瓶座',
		'12' => '双鱼座'
	];
}