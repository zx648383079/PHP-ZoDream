<?php
namespace App\Lib;
/******
时间处理类
*******/

class TimeDeal
{
	/***
	返回当前时间
	***/
	public static function Now($format = null)
	{
		date_default_timezone_set('Etc/GMT-8');     //这里设置了时区
		
		$time = time();
		
		if(!empty($format))
		{
			$time = date($format , $time);
		}
		return $time;
	}
}