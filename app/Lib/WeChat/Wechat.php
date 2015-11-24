<?php
namespace App\Lib\WeChat;
/**
 * 微信公众平台来来路认证，处理中心，消息分发
 */
use App;
use App\Model\LogModel;
use App\Lib\Object\OBase;
 
define("WECHAT_URL", 'http://www.lanecn.com');
define('WECHAT_TOKEN', App::config('wechat.token'));
define('ENCODING_AES_KEY', "MqAuKoex6FyT5No0OcpRyCicThGs0P1vz4mJ2gwvvkF");

/*
 * 开发者配置
*/
define("WECHAT_APPID", 'wx5d57f64bb4804d90');
define("WECHAT_APPSECRET", '4b1fa6d9442351ec9268eff05e38f521'); 

class Wechat extends OBase {

	private static $_instance;
	
	public static function getInstance() {
		if (!(self::$_instance instanceof self)) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	
	public function __clone() {}
	
    /**
     * 初始化，判断此次请求是否为验证请求，并以数组形式保存
     */
    public function __construct() {
    	$echostr = App::$request->get('echostr');
       	if (!empty($echostr) && $this->_checkSignature()) {
            die($echostr);
        }
        $poststr = file_get_contents('php://input'); //$GLOBALS["HTTP_RAW_POST_DATA"];
        $xml     = array();
        if (!empty($poststr)) {
        	LogModel::getInstance()->addWechat($poststr);
        	$xml = (array) simplexml_load_string($poststr, 'SimpleXMLElement', LIBXML_NOCDATA);
        }
        
        $this->_data = array_change_key_case($xml, CASE_LOWER);
    }
    
    /**
     * 解决不区分大小写
     * @see \App\Lib\Object\OBase::get()
     */
    public function get($key = null, $default = null) {
    	$key = strtolower($key);
    	return parent::get($key, $default);
    }

    /**
     * 判断验证请求的签名信息是否正确
     * @param  string $token 验证信息
     * @return boolean
     */
    private function _checkSignature() {
        $values = App::$request->get('signature,timestamp,nonce');
        $signatureArray = array(WECHAT_TOKEN, $values['timestamp'], $values['nonce']);
        sort($signatureArray, SORT_STRING);
        return sha1(implode($signatureArray)) == $values['signature'];
    }
    
    public function run() {
    	Request::switchType();
    }
}



