<?php
	/*
	*微信公众平台操作类
	*
	*
	*
	*/
	class WeChat{
		
		private $token;
		public $msgtype = 'text';   //('text','image','location')
    	public $msg = array();
		
		public function __construct($config_path)
		{
			$config=array();
			if(is_array($config_path)){
				$config=$config_path;
			}else{
				$config=require($config_path);
			}
			
			$this->token = $config['wechat']['token'];
	    }
		
		//获得用户发过来的消息（消息内容和消息类型  ）
	    public function getMsg()
	    {
	        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
	        if (!empty($postStr)) {
	            $this->msg = (array)simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
	            $this->msgtype = strtolower($this->msg['MsgType']);
	        }
			
			return $this->msg;
	    }
		
		 //回复文本消息
	    public function sendText($text='')
	    {
	        $CreateTime = time();
	        $FuncFlag = $this->setFlag ? 1 : 0;
	        $textTpl = "<xml>
	            <ToUserName><![CDATA[{$this->msg['FromUserName']}]]></ToUserName>
	            <FromUserName><![CDATA[{$this->msg['ToUserName']}]]></FromUserName>
	            <CreateTime>{$CreateTime}</CreateTime>
	            <MsgType><![CDATA[text]]></MsgType>
	            <Content><![CDATA[%s]]></Content>
	            <FuncFlag>0</FuncFlag>
	            </xml>";
	        return sprintf($textTpl,$text);
	    }
		
		//根据数组参数回复图文消息
	    public function sendNews($newsData=array())
	    {
	        $CreateTime = time();
	        $FuncFlag = $this->setFlag ? 1 : 0;
	        $newTplHeader = "<xml>
	            <ToUserName><![CDATA[{$this->msg['FromUserName']}]]></ToUserName>
	            <FromUserName><![CDATA[{$this->msg['ToUserName']}]]></FromUserName>
	            <CreateTime>{$CreateTime}</CreateTime>
	            <MsgType><![CDATA[news]]></MsgType>
	            <Content><![CDATA[%s]]></Content>
	            <ArticleCount>%s</ArticleCount><Articles>";
	        $newTplItem = "<item>
	            <Title><![CDATA[%s]]></Title>
	            <Description><![CDATA[%s]]></Description>
	            <PicUrl><![CDATA[%s]]></PicUrl>
	            <Url><![CDATA[%s]]></Url>
	            </item>";
	        $newTplFoot = "</Articles>
	            <FuncFlag>%s</FuncFlag>
	            </xml>";
	        $Content = '';
	        $itemsCount = count($newsData['items']);
	        $itemsCount = $itemsCount < 10 ? $itemsCount : 10;//微信公众平台图文回复的消息一次最多10条
	        if ($itemsCount) {
	            foreach ($newsData['items'] as $key => $item) {
	                if ($key<=9) {
	                    $Content .= sprintf($newTplItem,$item['title'],$item['description'],$item['picurl'],$item['url']);
	                }
	            }
	        }
	        $header = sprintf($newTplHeader,$newsData['content'],$itemsCount);
	        $footer = sprintf($newTplFoot,$FuncFlag);
	        return $header . $Content . $footer;
	    }
		
		public function valid(){
			$echoStr=$_GET['echostr'];
			if($this->checkSignature()){
				echo $echoStr;
				exit;
			}
		}
		
		private function checkSignature()
		{
		    $signature = $_GET["signature"];
		    $timestamp = $_GET["timestamp"];
		    $nonce = $_GET["nonce"];	
		        		
			$token = TOKEN;
			$tmpArr = array($token, $timestamp, $nonce);
			sort($tmpArr, SORT_STRING);
			$tmpStr = implode( $tmpArr );
			$tmpStr = sha1( $tmpStr );
			
			if( $tmpStr == $signature ){
				return true;
			}else{
				return false;
			}
		}
	}