<?php
    namespace App\Lib;
    //签名
    define ( 'TOKEN', config('wechat.token'));
    
	/**************************************************
	*微信公众平台操作类
	*
	*
	*
	****************************************************/
	class WeChat{
    
        //消息类型
        public static $msgtype;
    
        //消息内容
        public static $msgobj;
    
        //事件类型
        public static $eventtype;
    
        //事件key值
        public static $eventkey;
        
        //主要内容
        public static $mainMsg;
        
        public static $access_token;
    
    	#{服务号才可得到
        //AppId
        public static $appid = "";
        //AppSecret
        public static $secret = "";
    	#}
    	
    	/**
         *  初次校验
         */
    	public static function valid(){
            $echoStr = $_GET["echostr"];
	
	        //valid signature , option
	        if($this->checkSignature()){
	        	echo $echoStr;
	        }
        }
    
        /**
         *  创建自定义菜单
         */
        private function createMenu(){
            $url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token="
                    .$this->getAccessToken();
            $menujson = '{
                "button":[
                    {
                        "type":"click",
                        "name":"NAME1",
                        "key":"V1001_NEW"
                    },
                    {
                        "type":"view",
                        "name":"NAME2",
                        "url":"http://www.zhangenrui.cn"
                    },
                    {
                        "type":"view",
                        "name":"NAME3",
                        "url":"http://www.zhangenrui.cn"
                    }
                ]
            }';
    
            $ch = curl_init($url);
    
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS,$menujson);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    
            $info = curl_exec($ch);
    
            if (curl_errno($ch)) {
                echo 'Errno'.curl_error($ch);
            }
    
            curl_close($ch);
    
            var_dump($info);
        }
    
        /**
         *  删除自定义菜单
         */
        private function deleteMenu(){
            $url = "https://api.weixin.qq.com/cgi-bin/menu/delete?access_token="
                    .$this->getAccessToken();
    
            $ch = curl_init($url);
    
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    
            $info = curl_exec($ch);
    
            if (curl_errno($ch)) {
                echo 'Errno'.curl_error($ch);
            }
    
            curl_close($ch);
    
            var_dump($info);
    
        }
    
        /**
         *  获取消息
         */
        public static function getMsg(){
            //验证消息的真实性
            if(!$this->checkSignature()){
                exit();
            }
    
            //接收消息
            $poststr = $GLOBALS["HTTP_RAW_POST_DATA"];
            if(!empty($poststr)){
                self::$msgobj = simplexml_load_string($poststr,
                                'SimpleXMLElement',LIBXML_NOCDATA);
                self::$msgtype = strtolower( self::$msgobj-> MsgType);
            }
            else{
                self::$msgobj = null;
            }
            
            return self::$msgobj;
        }
    
        /**
         *  回复消息
         */
        private function responseMsg(){
            switch (self::$msgtype) {
                case 'text':
                    $data = $this->getData(self::$msgobj->Content);
                    if(empty($data) || !is_array($data)){
                        $content = "zx";
                    	self::$textMsg($content);//查询不到记录返回提示信息
                    }
                    else{
                    	$this->newsMsg($data);
                    }
                    break;
                case 'event':
                    $this->eventOpt();
                    break;
                case 'image':
                    //图片消息
                    break;
                case 'voice':
                    //语音消息
                    break;
                case 'video':
                    //视频消息
                    break;
                case 'shortvideo':
                    //小视频消息
                    break;
                case 'location':
                    //地理位置消息
                    break;
                case 'link':
                    //链接消息
                    break;
                default:
                    # code...
                    break;
            }
        }
    
        /**
         *  回复文本消息
         */
        public static function textMsg($content=''){
            $textxml = "<xml><ToUserName><![CDATA[{self::$msgobj->FromUserName}]]>
            </ToUserName><FromUserName><![CDATA[{self::$msgobj->ToUserName}]]>
            </FromUserName><CreateTime>".time()."</CreateTime><MsgType>
            <![CDATA[text]]></MsgType><Content><![CDATA[%s]]></Content></xml>";
            
            //做搜索处理
            if(empty($content)){
                $content = "查询功能正在开发中...";
            }
            $resultstr = sprintf($textxml,$content);
            echo $resultstr;
        }
        
        /**
         *  回复图片消息
         */
        public static function imgMsg($img){
            $imgxml = "<xml><ToUserName><![CDATA[{self::$msgobj->FromUserName}]]>
            </ToUserName><FromUserName><![CDATA[{self::$msgobj->ToUserName}]]>
            </FromUserName><CreateTime>".time()."</CreateTime><MsgType><![CDATA[image]]></MsgType>
            <Image><MediaId><![CDATA[%s]]></MediaId></Image></xml>";
            
            $resultstr = sprintf($imgxml,$img);
            echo $resultstr;
        }
        
        /**
         *  回复语音消息
         */
        public static function voiceMsg($voice){
            $voicexml = "<xml><ToUserName><![CDATA[{self::$msgobj->FromUserName}]]>
            </ToUserName><FromUserName><![CDATA[{self::$msgobj->ToUserName}]]>
            </FromUserName><CreateTime>".time()."</CreateTime><MsgType><![CDATA[voice]]></MsgType>
            <Voice><MediaId><![CDATA[%s]]></MediaId></Voice></xml>";
            
            $resultstr = sprintf($voicexml,$voice);
            echo $resultstr;
        }
    
        /**
         *  回复视频消息
         */
        public static function videoMsg($video,$title="",$description=""){
            $videoxml = "<xml><ToUserName><![CDATA[{self::$msgobj->FromUserName}]]>
            </ToUserName><FromUserName><![CDATA[{self::$msgobj->ToUserName}]]>
            </FromUserName><CreateTime>".time()."</CreateTime><MsgType><![CDATA[video]]></MsgType>
            <Video><MediaId><![CDATA[%s]]></MediaId><Title><![CDATA[%s]]></Title>
            <Description><![CDATA[%s]]></Description></Video></xml>";
            
            $resultstr = sprintf($videoxml,$video,$title,$description);
            echo $resultstr;
        }
        
        /**
         *  回复音乐消息
         */
        public static function musicMsg($pic,$music="",$title="",$description="",$hgmusic=""){
            $musicxml = "<xml><ToUserName><![CDATA[{self::$msgobj->FromUserName}]]>
            </ToUserName><FromUserName><![CDATA[{self::$msgobj->ToUserName}]]>
            </FromUserName><CreateTime>".time()."</CreateTime><MsgType><![CDATA[music]]></MsgType>
            <Music><Title><![CDATA[%s]]></Title><Description><![CDATA[%s]]></Description>
            <MusicUrl><![CDATA[%s]]></MusicUrl><HQMusicUrl><![CDATA[%s]]></HQMusicUrl>
            <ThumbMediaId><![CDATA[%s]]></ThumbMediaId></Music></xml>";
            
            $resultstr = sprintf($musicxml,$title,$description,$music,$hgmusic,$pic);
            echo $resultstr;
        }
        
        /**
         *  回复图文消息
         */
        public static function newsMsg($data){
            if(!is_array($data)){
            	exit();
            }
            $newscount = (count($data) > 10)?10:count($data);
            $newsxml = "<xml><ToUserName><![CDATA[{self::$msgobj->FromUserName}]]></ToUserName><FromUserName>
            <![CDATA[{self::$msgobj->ToUserName}]]></FromUserName><CreateTime>".time()."</CreateTime><MsgType>
            <![CDATA[news]]></MsgType><ArticleCount>{$newscount}</ArticleCount><Articles>%s</Articles></xml>";
            $itemxml = "";
            foreach ($data as $key => $value) {
            	$itemxml .= "<item>";
            	$itemxml .= "<Title><![CDATA[{$value['title']}]]></Title><Description><![CDATA[{$value['description']}]]>
                </Description><PicUrl><![CDATA[{$value['pic']}]]></PicUrl><Url><![CDATA[{$value['url']}]]></Url>";
            	$itemxml .= "</item>";
            }
            $resultstr = sprintf($newsxml,$itemxml);
            echo $resultstr;
        }
    
        /**
         *  事件处理
         */
        public static function eventOpt(){
            self::$eventtype = strtolower(self::$msgobj->Event);
            switch (self::$eventtype) {
                case 'subscribe':
    
                    //做用户绑定处理 
                    if(!empty(self::$msgobj->EventKey))
                    {
                        //新关注扫码
                    }
                    $content = "欢迎";
                    self::$textMsg($content);
                    break;
                case 'unsubscribe':
                    
                    //做用户取消绑定的处理
    
                    break;
                case 'click':
                    $this->menuClick();
                    break;
                case 'view':
                    //点击菜单跳转链接时的事件推送 
                    break;
                case 'scan':
                    //已关注扫码
                    break;
                case 'location':
                    //上报地理位置
                    break;
                default:
                    # code...
                    break;
            }
        }
    
        /**
         *  自定义菜单事件处理
         */
        private function menuClick(){
            self::$eventkey = self::$msgobj->EventKey;
            switch (self::$eventkey) {
                case 'V1001_NEW':
                	$data = $this->getData();
                    $this->newsMsg($data);
                    break;
                default:
                    # code...
                    break;
            }
        }
    

    	
        /**
         *  校验签名
         */
    	private function checkSignature(){
            $signature = $_GET["signature"];
	        $timestamp = $_GET["timestamp"];
	        $nonce = $_GET["nonce"];
	        		
			$token = TOKEN;
			$tmpArr = array($token, $timestamp, $nonce);
	        // use SORT_STRING rule
			sort($tmpArr, SORT_STRING);
			$tmpStr = implode( $tmpArr );
			$tmpStr = sha1( $tmpStr );
			
			if( $tmpStr == $signature ){
				return true;
			}else{
				return false;
			}
    	}
    
        /**
         *  获取access token
         */
        private function getAccessToken()
        {
            $url = config('wechat.access_token');
            $atjson=file_get_contents($url);
            $result=json_decode($atjson,true);//json解析成数组
            
            self::$access_token = isset($result['access_token']) ? $result['access_token'] :'';
            /*if(!isset($result['access_token']))
            {
                //exit( '获取access_token失败！' );
                
            }*/
            return $result["access_token"];
        }
        
        public static function getMainMsg()
        {
            $mainMsg['openid'] = self::$msgobj->FromUserName;
            $mainMsg['type'] = self::$msgtype;
            
            switch (self::$msgtype) {
                case 'text':
                    
                case 'event':
                    $this->eventOpt();
                    break;
                case 'image':
                    //图片消息
                    break;
                case 'voice':
                    //语音消息
                    break;
                case 'video':
                    //视频消息
                    break;
                case 'shortvideo':
                    //小视频消息
                    break;
                case 'location':
                    //地理位置消息
                    break;
                case 'link':
                    //链接消息
                    break;
                default:
                    # code...
                    break;
            }
            
            
            return self::$mainMsg;
        }
		
	}