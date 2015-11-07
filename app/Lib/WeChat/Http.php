<?php 
namespace App\Lib\WeChat;

class Http {
	public static function get($url) {
		return self::request($url);
	}
	
	public static function post($url) {
		return self::request($url, 'POST');
	}
	
	public static function request($url, $method = 'GET') {
		
	}
	
	public static function deJson($json) {
		
	}
	
	public static function enJson($jsonArray) {
		
	}
	
	public static function deXml($xml) {
		
	}
	
	public static function enXml($xmlArray) {
		
	}
}