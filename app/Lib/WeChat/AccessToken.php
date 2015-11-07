<?php 
namespace App\Lib\WeChat;

class AccessToken {
	const URL = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential';
	
	public static $accessToken = null;
	
	public static function get($appid, $secret) {
		$jsonArray = Http::get(self::URL.'&appid='.$appid.'&secret='.$secret);
		if (array_key_exists('access_token', $jsonArray)) {
			return self::$accessToken = $jsonArray['access_token'];
		} else if (array_key_exists('errcode', $jsonArray)) {
			return Error::get($jsonArray['errcode']);
		} else {
			return NULL;
		}
	}
}