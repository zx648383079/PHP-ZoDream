<?php 
namespace App\Lib\WeChat;
/**
* 模板
*/
class Templet {
	private static $_industries = array(
		'IT科技' => array(
			1 => '互联网/电子商务',
			2 => 'IT软件与服务',
			3 => 'IT硬件与设备',
			4 => '电子技术',
			5 => '通信与运营商',
			6 => '网络游戏',
		),
	
		'金融业' => array(
			7 => '银行',
			8 => '基金|理财|信托',
			9 => '保险',
		),
	
		'餐饮'  => array(
			10 => '餐饮'
		),
	
		'酒店旅游' => array(
			11 => '酒店',
			12 => '旅游',
		),
	
		'运输与仓储' => array(
			13 => '快递',
			14 => '物流',
			14 => '仓储',
		),
	
		'教育' => array(
			16 => '培训',
			17 => '院校',
		),
	
		'政府与公共事业' => array(
			18 => '学术科研',
			19 => '交警',
			20 => '博物馆',
			21 => '公共事业|非盈利机构',
		),
	
		'医药护理' => array(
			22 => '医药医疗',
			23 => '护理美容',
			24 => '保健与卫生',
		),
	
		'交通工具' => array(
			25 => '汽车相关',
			26 => '摩托车相关',
			27 => '火车相关',
			28 => '飞机相关',
		),
	
		'房地产' => array(
			29 => '建筑',
			30 => '物业',
		),
	
		'消费品' => array(
			31 => '消费品'
		),
	
		'商业服务' => array(
			32 => '法律',
			33 => '会展',
			34 => '中介服务',
			35 => '认证',
			36 => '审计',
		),
	
		'文体娱乐' => array(
			37 => '传媒',
			38 => '体育',
			39 => '娱乐休闲',
		),
	
		'印刷' => array(
			40 => '印刷'
		),
	
		'其它' => array(
			41 => '其它'
		),
	);
	
	public static function setIndustry($data) {
		$jsonArray = Http::post('https://api.weixin.qq.com/cgi-bin/template/api_set_industry?access_token='.AccessToken::$access_token, $data);
		return $jsonArray;
	}
	
	public static function getTempletId($data) {
		$jsonArray = Http::post('https://api.weixin.qq.com/cgi-bin/template/api_add_template?access_token='.AccessToken::$access_token, $data);
		if ($jsonArray['errcode'] === 0) {
			return $jsonArray['template_id'];
		}
		return Error::get($jsonArray['errcode']);
	}
	
	public static function send($data) {
		$jsonArray = Http::post('https://api.weixin.qq.com/cgi-bin/message/template/send?access_token='.AccessToken::$access_token, $data);
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