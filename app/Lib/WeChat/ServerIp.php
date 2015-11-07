<?php 
namespace App\Lib\WeChat;

class ServerIp {
	const URL = 'https://api.weixin.qq.com/cgi-bin/getcallbackip?access_token=';
	
	public static function get() {
		$jsonArray = Http::get(self::URL.AccessToken::$access_token);
		if (array_key_exists('ip_list', $jsonArray)) {
			return $jsonArray['ip_list'];
		} else if (array_key_exists('errcode', $jsonArray)) {
			return Error::get($jsonArray['errcode']);
		} else {
			return NULL;
		}
	}
}