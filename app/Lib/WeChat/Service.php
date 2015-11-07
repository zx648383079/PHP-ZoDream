<?php 
namespace App\Lib\WeChat;

class Service {
	public static function add($data) {
		$jsonArray = Http::post('https://api.weixin.qq.com/customservice/kfaccount/add?access_token='.AccessToken::$access_token, $data);
		return Error::get($jsonArray['errcode']);
	}
	
	public static function update($data) {
		$jsonArray = Http::post('https://api.weixin.qq.com/customservice/kfaccount/update?access_token='.AccessToken::$access_token, $data);
		return Error::get($jsonArray['errcode']);
	}
	
	public static function delete($data) {
		$jsonArray = Http::post('https://api.weixin.qq.com/customservice/kfaccount/del?access_token='.AccessToken::$access_token, $data);
		return Error::get($jsonArray['errcode']);
	}
	
	public static function photo($kfAccount, $image) {
		$jsonArray = Http::image('http://api.weixin.qq.com/customservice/kfaccount/uploadheadimg?access_token='.AccessToken::$access_token.'&kf_account='.$kfAccount, $image);
		return Error::get($jsonArray['errcode']);
	}
	
	public static function getAll() {
		$jsonArray = Http::get('https://api.weixin.qq.com/cgi-bin/customservice/getkflist?access_token='.AccessToken::$access_token);
		return $jsonArray;
	}
	
	public static function send($data) {
		$jsonArray = Http::post('https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token='.AccessToken::$access_token, $data);
		return Error::get($jsonArray['errcode']);
	}
}