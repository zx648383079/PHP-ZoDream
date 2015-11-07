<?php 
namespace App\Lib\WeChat;
/**
* 获取自动回复规则
*/
class Material {
	public static function upload($data, $type) {
		$jsonArray = Http::upload('https://api.weixin.qq.com/cgi-bin/media/upload?access_token='.AccessToken::$access_token.'&type='.$type);
		return $jsonArray;
	}
	
	public static function get($media_id) {
		$jsonArray = Http::get('https://api.weixin.qq.com/cgi-bin/media/get?access_token='.AccessToken::$access_token.'&media_id='.$media_id);
		return $jsonArray;
	}
	
	public static function addMessage($data) {
		$jsonArray = Http::post('https://api.weixin.qq.com/cgi-bin/material/add_news?access_token='.AccessToken::$access_token, $data);
		return $jsonArray;
	}
	
	public static function updateMessage($data) {
		$jsonArray = Http::post('https://api.weixin.qq.com/cgi-bin/material/update_news?access_token='.AccessToken::$access_token, $data);
		return $jsonArray;
	}
	
	public static function addMaterial($data) {
		$jsonArray = Http::post('https://api.weixin.qq.com/cgi-bin/material/add_material?access_token='.AccessToken::$access_token, $data);
		return $jsonArray;
	}
	
	public static function getMaterial($data) {
		$jsonArray = Http::post('https://api.weixin.qq.com/cgi-bin/material/get_material?access_token='.AccessToken::$access_token, $data);
		return $jsonArray;
	}
	
	public static function deleteMaterial($data) {
		$jsonArray = Http::post('https://api.weixin.qq.com/cgi-bin/material/del_material?access_token='.AccessToken::$access_token, $data);
		return $jsonArray;
	}
	
	public static function getCount() {
		$jsonArray = Http::get('https://api.weixin.qq.com/cgi-bin/material/get_materialcount?access_token='.AccessToken::$access_token);
		return $jsonArray;
	}
	
	public static function getList($data) {
		$jsonArray = Http::post('https://api.weixin.qq.com/cgi-bin/material/batchget_material?access_token='.AccessToken::$access_token, $data);
		return $jsonArray;
	}
}