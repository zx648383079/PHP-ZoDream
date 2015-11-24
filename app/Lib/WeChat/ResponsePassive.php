<?php
namespace App\Lib\WeChat;
/**
 * 发送被动响应
 */

class ResponsePassive {
	
	/**
	 * 响应
	 * @param array $data
	 */
	public static function response($data) {
		if (empty(self::$message)) {
			self::get();
		}
		echo '<xml><ToUserName><![CDATA[', Wechat::getInstance()->get('FromUserName'),
		']]></ToUserName><FromUserName><![CDATA[',Wechat::getInstance()->get('ToUserName'),
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
		return is_numeric($data) ? $data : sprintf('<![CDATA[%s]]>', $data);
	}
	
	
    /**
     * 回复文本消息
     * @param string $content 回复的消息内容
     */
	public static function text($content = '我们已收到您的信息！') {
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
     * 一条图文消息的构成
     * @param string $title       图文消息标题
     * @param string $description 图文消息描述 
     * @param string $picUrl      图片链接
     * @param string $url         点击图文消息跳转链接 
     * @return array
     */
    public static function newsItem ($title = null, $description = null, $picUrl = null, $url = null) {
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
     * 回复图文消息 多条图文消息信息，默认第一个item为大图,注意，如果调用本方法得到的数组总项数超过10，则将会无响应
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
     * 将消息转发到多客服
     * 如果公众号处于开发模式，需要在接收到用户发送的消息时，返回一个MsgType为transfer_customer_service的消息，微信服务器在收到这条消息时，会把这次发送的消息转到多客服系统。用户被客服接入以后，客服关闭会话以前，处于会话过程中，用户发送的消息均会被直接转发至客服系统。
     * @param $fromusername
     * @param $tousername
     * @return string
     */
    public static function forwardToCustomService($fromusername, $tousername){
        $template = <<<XML
<xml>
    <ToUserName><![CDATA[%s]]></ToUserName>
    <FromUserName><![CDATA[%s]]></FromUserName>
    <CreateTime>%s</CreateTime>
    <MsgType><![CDATA[transfer_customer_service]]></MsgType>
</xml>
XML;
        return sprintf($template, $fromusername, $tousername, time());
    }
}