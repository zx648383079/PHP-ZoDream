<?php 
namespace App\Lib\WeChat;
/**
* 获取自动回复规则
*/
class ReplyRule {
	public static function get() {
		return Curl::get('https://api.weixin.qq.com/cgi-bin/get_current_autoreply_info?access_token='.AccessToken::getAccessToken());
	}
}