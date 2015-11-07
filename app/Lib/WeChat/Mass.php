<?php 
namespace App\Lib\WeChat;
/**
* 群发
*/
class Mass {
	public static function uploadImage($image) {
		$jsonArray = Http::image('https://api.weixin.qq.com/cgi-bin/media/uploadimg?access_token='.AccessToken::$access_token, $image);
		if (array_key_exists('url', $jsonArray)) {
			return $jsonArray['url'];
		}
		return Error::get($jsonArray['errcode']);
	}
	
	public static function uploadMessage($data) {
		$jsonArray = Http::post('https://api.weixin.qq.com/cgi-bin/media/uploadnews?access_token='.AccessToken::$access_token, $data);
		return $jsonArray;
	}
	
	public static function sendAll($data) {
		$jsonArray = Http::post('https://api.weixin.qq.com/cgi-bin/message/mass/sendall?access_token='.AccessToken::$access_token, $data);
		return Error::get($jsonArray['errcode']);
	}
	
	public static function send($data) {
		$jsonArray = Http::post('https://api.weixin.qq.com/cgi-bin/message/mass/send?access_token='.AccessToken::$access_token, $data);
		return Error::get($jsonArray['errcode']);
	}
	
	public static function delete($data) {
		$jsonArray = Http::post('https://api.weixin.qq.com/cgi-bin/message/mass/delete?access_token='.AccessToken::$access_token, $data);
		return Error::get($jsonArray['errcode']);
	}
	
	public static function preview($data) {
		$jsonArray = Http::post('https://api.weixin.qq.com/cgi-bin/message/mass/preview?access_token='.AccessToken::$access_token, $data);
		return Error::get($jsonArray['errcode']);
	}
	
	public static function get($data) {
		$jsonArray = Http::post('https://api.weixin.qq.com/cgi-bin/message/mass/get?access_token='.AccessToken::$access_token, $data);
		return Error::get($jsonArray['msg_status']);
	}
}