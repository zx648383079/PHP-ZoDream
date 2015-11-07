<?php
namespace App\Lib\WeChat;
/**************************************************
 * 消息类
 *
 *
 *
 ****************************************************/
use App;
use App\Model\LogModel;

define ('TOKEN', App::config('wechat.token'));

class Message {
    public static $message;
    
    public static $access_token;
	
    /**
     * 首次验证
     */
    public static function valid() {
        $echoStr = App::$request->get['echostr'];
        if (self::_checkSignature()) {
            echo $echoStr;
        }
    }
	
    /**
     * 获取消息
     */
    public static function get()
    {
    	if (empty(self::$message)) {
    		if (!self::_checkSignature()) {
    			exit();
    		}
    		$poststr = $GLOBALS["HTTP_RAW_POST_DATA"];
    		if (!empty($poststr)) {
    			LogModel::getInstance()->addWechat($poststr);
    			self::$message = simplexml_load_string($poststr, 'SimpleXMLElement', LIBXML_NOCDATA);
    		} else {
    			self::$message = null;
    		}	
    	}
        return self::$message;
    }
    
    private static function _xmlToArray($data) {
    	if (is_object($data) && get_class($data) === 'SimpleXMLElement') {
    		$data = (array) $data;
    	} 
    	if (is_array($data)) {
    		foreach ($data as $index => $value) {
    			$data[$index] = self::_xmlToArray($value);
    		}
    	}	 
    	return $data;
    }
    
    /**
     * 响应
     * @param array $data
     */
    public static function response($data) {
    	if (empty(self::$message)) {
    		self::get();
    	}
    	echo '<xml><ToUserName><![CDATA[',self::$message->ToUserName,
    		 ']]></ToUserName><FromUserName><![CDATA[',self::$message->FromUserName,
    		 ']]></FromUserName><CreateTime>',time(),'</CreateTime>',self::_xml($data),'</xml>';
    }
    
    /**
     * 将数组转化成XML
     * @param array|object|string $data
     * @return string
     */
    private static function _xml($data) {
    	if (is_object($data)) {
    		$data = (array)$data;
    	}
    	if (is_array($data)) {
    		$str = '';
    		foreach ($data as $key => $value) {
    			$tag = is_int($key) ? 'item' : $key;                            //文章
    			$str .= sprintf('<%s>%s</%s>', $tag, self::_xml($value), $tag);
    		}
    		return $str;
    	}
    	return is_numeric($val) ? $val : sprintf('<![CDATA[%s]]>', $data);
    }
    
    /**
     * 回复文本消息
     * @param string $content 回复的消息内容
     */
    public static function text($content = '') {
    	self::response(array(
    		'MsgType' => 'text',
    		'Content' => $content
    	));
    }
    
    /**
     * 回复图片消息
     * @param string $mediaId 通过素材管理接口上传多媒体文件，得到的id。 
     */
    public static function image($mediaId) {
    	self::response(array(
    		'MsgType' => 'image',
    		'Image'   => array(
    			'MediaId' => $mediaId
    		)
    	));
    }
    
    /**
     * 回复语音消息
     * @param string $mediaId 通过素材管理接口上传多媒体文件，得到的id 
     */
    public static function voice($mediaId) {
    	self::response(array(
    			'MsgType' => 'voice',
    			'Voice'   => array(
    					'MediaId' => $mediaId
    			)
    	));
    }
    
    /**
     * 回复视频消息
     * @param string $mediaId     通过素材管理接口上传多媒体文件，得到的id 
     * @param string $title       视频消息的标题 
     * @param string $description 视频消息的描述
     */
    public static function video($mediaId, $title = null, $description = null) {
    	$data = array(
    		'MediaId' => $mediaId
    	);
    	if (!empty($title)) {
    		$data['Title'] = $title;
    	}
    	if (!empty($description)) {
    		$data['Description'] = $description;
    	}
    	self::response(array(
    		'MsgType' => 'video',
    		'Video'   => $data
    	));
    }
    /**
     * 回复音乐消息
     * @param unknown $musicUrl    音乐链接
     * @param string $title        音乐标题  
     * @param string $description  音乐描述
     * @param string $hQMusicUrl   高质量音乐链接
     * @param string $thumbMediaId 缩略图的媒体id
     */
    public static function music($musicUrl, $title = null, $description = null, $hQMusicUrl = null, $thumbMediaId = null) {
    	$data = array(
    		'MusicUrl' => $musicUrl
    	);
    	if (!empty($title)) {
    		$data['Title'] = $title;
    	}
    	if (!empty($description)) {
    		$data['Description'] = $description;
    	}
    	if (!empty($hQMusicUrl)) {
    		$data['HQMusicUrl'] = $hQMusicUrl;
    	}
    	if (!empty($thumbMediaId)) {
    		$data['ThumbMediaId'] = $thumbMediaId;
    	}
    	self::response(array(
    			'MsgType' => 'music',
    			'Music'   => $data
    	));
    }
    
    /**
     * 回复图文消息
     * @param array $data
     */
    public static function news($data) {
    	if(array_key_exists('Title', $data)) {
    		$data = array($data);
    	}
    	self::response(array(
    			'MsgType'      => 'news',
    			'ArticleCount' => count($data),
    			'Articles'     => $data
    	));
    }
    
    /**
     * 一条图文消息的构成
     * @param string $title       图文消息标题
     * @param string $description 图文消息描述 
     * @param string $picUrl      图片链接
     * @param string $url         点击图文消息跳转链接 
     * @return array
     */
    public static function aNews ($title = null, $description = null, $picUrl = null, $url = null) {
    	$data = array();
    	if (!empty($title)) {
    		$data['Title'] = $title;
    	}
    	if (!empty($description)) {
    		$data['Description'] = $description;
    	}
    	if (!empty($picUrl)) {
    		$data['PicUrl'] = $picUrl;
    	}
    	if (!empty($url)) {
    		$data['Url'] = $url;
    	}
    	return $data;
    }
    
    /**
     * 验证
     * @return boolean
     */
    private static function _checkSignature() {
        $values = App::$request->get['signature,timestamp,nonce'];
        $token  = TOKEN;
        $tmpArr = array($token, $values['timestamp'], $values['nonce']);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);
        if ($tmpStr == $values['signature']) {
            return true;
        } else {
            return false;
        }
    }   
}