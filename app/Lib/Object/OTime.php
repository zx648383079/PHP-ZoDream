<?php
namespace App\Lib\Object;
/******
时间处理类
*******/

class OTime implements IBase
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
	
	public static function to($arg)
	{
		return date( 'Y-m-d H:i:s' , $arg);
	}
}