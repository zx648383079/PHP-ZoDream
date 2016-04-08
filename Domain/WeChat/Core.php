<?php
namespace Domain\WeChat;
/**
 * Created by PhpStorm.
 * User: zx648
 * Date: 2016/3/9
 * Time: 10:37
 */
use Zodream\Infrastructure\Config;
use Zodream\Infrastructure\Error;
use Zodream\Infrastructure\Request;
abstract class Core {
    /**
     * @var static
     */
    protected static $instance;
    public static function WeChat() {
        if (empty(self::$instance)) {
            self::$instance = new static;
        }
        return self::$instance;
    }

    const MSGTYPE_TEXT = 'text';
    const MSGTYPE_IMAGE = 'image';
    const MSGTYPE_LOCATION = 'location';
    const MSGTYPE_LINK = 'link';
    const MSGTYPE_EVENT = 'event';
    const MSGTYPE_MUSIC = 'music';
    const MSGTYPE_NEWS = 'news';
    const MSGTYPE_VOICE = 'voice';
    const MSGTYPE_VIDEO = 'video';
    const EVENT_SUBSCRIBE = 'subscribe';       //订阅
    const EVENT_UNSUBSCRIBE = 'unsubscribe';   //取消订阅

    const EVENT_LOCATION = 'LOCATION';         //上报地理位置
    const EVENT_MENU_VIEW = 'VIEW';                     //菜单 - 点击菜单跳转链接
    const EVENT_MENU_CLICK = 'CLICK';                   //菜单 - 点击菜单拉取消息
    const EVENT_MENU_SCAN_PUSH = 'scancode_push';       //菜单 - 扫码推事件(客户端跳URL)
    const EVENT_MENU_SCAN_WAITMSG = 'scancode_waitmsg'; //菜单 - 扫码推事件(客户端不跳URL)
    const EVENT_MENU_PIC_SYS = 'pic_sysphoto';          //菜单 - 弹出系统拍照发图
    const EVENT_MENU_PIC_PHOTO = 'pic_photo_or_album';  //菜单 - 弹出拍照或者相册发图
    const EVENT_MENU_PIC_WEIXIN = 'pic_weixin';         //菜单 - 弹出微信相册发图器
    const EVENT_MENU_LOCATION = 'location_select';      //菜单 - 弹出地理位置选择器
    const EVENT_SEND_MASS = 'MASSSENDJOBFINISH';        //发送结果 - 高级群发完成
    const EVENT_SEND_TEMPLATE = 'TEMPLATESENDJOBFINISH';//发送结果 - 模板消息发送结果

    const MENU_CREATE_URL = '/menu/create?';
    const MENU_GET_URL = '/menu/get?';
    const MENU_DELETE_URL = '/menu/delete?';
    const CALLBACKSERVER_GET_URL = '/getcallbackip?';
    const USER_GET_URL = '/user/get?';
    const MEDIA_UPLOAD_URL = '/media/upload?';
    const MEDIA_GET_URL = '/media/get?';
    const OAUTH_PREFIX = 'https://open.weixin.qq.com/connect/oauth2';
    const OAUTH_AUTHORIZE_URL = '/authorize?';

    protected $token;
    protected $jsApiTicket;
    protected $encodingAesKey;
    protected $encryptType;
    protected $appId;
    protected $appSecret;
    protected $accessToken;
    protected $postXml;
    protected $msg;
    protected $receive;

    protected $textFilter = true;
    public $debug =  false;
    public $errCode = 40001;
    public $errMsg = 'no access';
    public $logCallback;


    public function __construct() {
        $config = Config::getInstance()->get('wechat');
        foreach ($config as $key => $value) {
            $this->$key = $value;
        }
    }

    protected function checkSignature($str = '') {
        $signature = Request::get('signature');
        $signature = Request::get('msg_signature', $signature); //如果存在加密验证则用加密验证段
        $timestamp = Request::get('timestamp');
        $nonce = Request::get('nonce');

        $token = $this->token;
        $tmpArr = array($token, $timestamp, $nonce, $str);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );
        return $tmpStr == $signature;
    }

    public function valid($return = false) {
        $encryptStr="";
        if (Request::isPost()) {
            $postStr = Request::input();
            $array = (array)simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $this->encryptType = Request::get('encrypt_type');
            if ($this->encryptType == 'aes') { //aes加密
                $this->log($postStr);
                $encryptStr = $array['Encrypt'];
                $pc = new PrpCrypt($this->encodingAesKey);
                $array = $pc->decrypt($encryptStr,$this->appId);
                if (!isset($array[0]) || ($array[0] != 0)) {
                    if (!$return) {
                        Error::out('DECRYPT ERROR!', __FILE__, __LINE__);
                    } else {
                        return false;
                    }
                }
                $this->postXml = $array[1];
                if (!$this->appId)
                    $this->appId = $array[2];//为了没有appid的订阅号。
            } else {
                $this->postXml = $postStr;
            }
        } elseif (null != ($echoStr = Request::get('echostr'))) {
            if ($return) {
                if ($this->checkSignature())
                    return $echoStr;
                else
                    return false;
            } else {
                if ($this->checkSignature())
                    exit($echoStr);
                else
                    Error::out('no access', __FILE__, __LINE__);
            }
        }

        if (!$this->checkSignature($encryptStr)) {
            if ($return)
                return false;
            else
                Error::out('no access', __FILE__, __LINE__);
        }
        return true;
    }

    public static function xmlSafeStr($str) {
        return '<![CDATA['. preg_replace("/[\\x00-\\x08\\x0b-\\x0c\\x0e-\\x1f]/", '', $str). ']]>';
    }

    /**
     * 数据XML编码
     * @param mixed $data 数据
     * @return string
     */
    public static function dataToXml($data) {
        $xml = '';
        foreach ($data as $key => $val) {
            is_numeric($key) && $key = "item id=\"$key\"";
            $xml    .=  "<$key>";
            $xml    .=  ( is_array($val) || is_object($val)) ? self::dataToXml($val)  : self::xmlSafeStr($val);
            list($key, ) = explode(' ', $key);
            $xml    .=  "</$key>";
        }
        return $xml;
    }

    /**
     * XML编码
     * @param mixed $data 数据
     * @param string $root 根节点名
     * @param string $item 数字索引的子节点名
     * @param string $attr 根节点属性
     * @param string $id   数字索引子节点key转换的属性名
     * @param string $encoding 数据编码
     * @return string
     */
    public function xmlEncode($data, $root = 'xml', $item = 'item', $attr = '', $id = 'id', $encoding = 'utf-8') {
        if(is_array($attr)){
            $_attr = array();
            foreach ($attr as $key => $value) {
                $_attr[] = "{$key}=\"{$value}\"";
            }
            $attr = implode(' ', $_attr);
        }
        $attr   = trim($attr);
        $attr   = empty($attr) ? '' : " {$attr}";
        $xml   = "<{$root}{$attr}>";
        $xml   .= self::dataToXml($data, $item, $id);
        $xml   .= "</{$root}>";
        return $xml;
    }

    /**
     * 微信api不支持中文转义的json结构
     * @param array $arr
     */
    public static function jsonEncode($arr) {
        $parts = array ();
        $is_list = false;
        //Find out if the given array is a numerical array
        $keys = array_keys ( $arr );
        $max_length = count ( $arr ) - 1;
        if (($keys [0] === 0) && ($keys [$max_length] === $max_length )) { //See if the first key is 0 and last key is length - 1
            $is_list = true;
            for($i = 0; $i < count ( $keys ); $i ++) { //See if each key correspondes to its position
                if ($i != $keys [$i]) { //A key fails at position check.
                    $is_list = false; //It is an associative array.
                    break;
                }
            }
        }
        foreach ( $arr as $key => $value ) {
            if (is_array ( $value )) { //Custom handling for arrays
                if ($is_list)
                    $parts [] = self::jsonEncode( $value ); /* :RECURSION: */
                else
                    $parts [] = '"' . $key . '":' . self::jsonEncode( $value ); /* :RECURSION: */
            } else {
                $str = '';
                if (! $is_list)
                    $str = '"' . $key . '":';
                //Custom handling for multiple data types
                if (!is_string ( $value ) && is_numeric ( $value ) && $value<2000000000)
                    $str .= $value; //Numbers
                elseif ($value === false)
                    $str .= 'false'; //The booleans
                elseif ($value === true)
                    $str .= 'true';
                else
                    $str .= '"' .addcslashes($value, "\\\"\n\r\t/"). '"'; //All other things
                $parts [] = $str;
            }
        }
        $json = implode ( ',', $parts );
        if ($is_list)
            return '[' . $json . ']'; //Return numerical JSON
        return '{' . $json . '}'; //Return associative JSON
    }

    protected function log($log){
        if ($this->debug && function_exists($this->logCallback)) {
            if (is_array($log)) $log = print_r($log,true);
            return call_user_func($this->logCallback,$log);
        }
        return false;
    }

    /**
     * 过滤文字回复\r\n换行符
     * @param string $text
     * @return string|mixed
     */
    protected function autoTextFilter($text) {
        if (!$this->textFilter) return $text;
        return str_replace("\r\n", "\n", $text);
    }

    /**
     * GET 请求
     * @param string $url
     */
    protected function httpGet($url){
        $oCurl = curl_init();
        if(stripos($url,"https://")!==FALSE){
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, FALSE);
            curl_setopt($oCurl, CURLOPT_SSLVERSION, 1); //CURL_SSLVERSION_TLSv1
        }
        curl_setopt($oCurl, CURLOPT_URL, $url);
        curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1 );
        $sContent = curl_exec($oCurl);
        $aStatus = curl_getinfo($oCurl);
        curl_close($oCurl);
        if(intval($aStatus["http_code"])==200){
            return $sContent;
        }else{
            return false;
        }
    }

    /**
     * POST 请求
     * @param string $url
     * @param array $param
     * @param boolean $post_file 是否文件上传
     * @return string content
     */
    protected function httpPost($url, $param, $post_file = false){
        $oCurl = curl_init();
        if(stripos($url,"https://")!==FALSE){
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($oCurl, CURLOPT_SSLVERSION, 1); //CURL_SSLVERSION_TLSv1
        }
        if (is_string($param) || $post_file) {
            $strPOST = $param;
        } else {
            $aPOST = array();
            foreach($param as $key=>$val){
                $aPOST[] = $key."=".urlencode($val);
            }
            $strPOST =  join("&", $aPOST);
        }
        curl_setopt($oCurl, CURLOPT_URL, $url);
        curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt($oCurl, CURLOPT_POST,true);
        curl_setopt($oCurl, CURLOPT_POSTFIELDS,$strPOST);
        $sContent = curl_exec($oCurl);
        $aStatus = curl_getinfo($oCurl);
        curl_close($oCurl);
        if(intval($aStatus["http_code"])==200){
            return $sContent;
        }else{
            return false;
        }
    }

    /**
     * 设置发送消息
     * @param array $msg 消息数组
     * @param bool $append 是否在原消息数组追加
     */
    public function message($msg = '', $append = false){
        if (is_null($msg)) {
            $this->msg = array();
        }elseif (is_array($msg)) {
            if ($append)
                $this->msg = array_merge($this->msg,$msg);
            else
                $this->msg = $msg;
        }
        return $this->msg;
    }

    /**
     * 获取微信服务器发来的信息
     */
    public function getRev() {
        if ($this->receive) {
            return $this;
        }
        if (empty($this->postXml)) {
            $this->postXml = Request::input();
        }
        //兼顾使用明文又不想调用valid()方法的情况
        $this->log($this->postXml);
        if (!empty($this->postXml)) {
            $this->receive = (array)simplexml_load_string($this->postXml, 'SimpleXMLElement', LIBXML_NOCDATA);
        }
        return $this;
    }

    /**
     * 获取微信服务器发来的信息
     */
    public function getRevData() {
        return $this->receive;
    }

    /**
     * 获取微信服务器发来的原始加密信息
     */
    public function getRevPostXml() {
        return $this->postXml;
    }

    /**
     * 获取消息发送者
     */
    public function getRevFrom() {
        if (isset($this->receive['FromUserName'])) {
            return $this->receive['FromUserName'];
        }
        return false;
    }

    /**
     * 获取消息接受者
     */
    public function getRevTo() {
        if (isset($this->receive['ToUserName'])) {
            return $this->receive['ToUserName'];
        }
        return false;
    }

    /**
     * 获取接收消息的类型
     */
    public function getRevType() {
        if (isset($this->receive['MsgType'])) {
            return $this->receive['MsgType'];
        }
        return false;
    }

    /**
     * 获取消息ID
     */
    public function getRevID()
    {
        if (isset($this->receive['MsgId'])) {
            return $this->receive['MsgId'];
        }
        return false;
    }

    /**
     * 获取消息发送时间
     */
    public function getRevCreateTime() {
        if (isset($this->receive['CreateTime'])) {
            return $this->receive['CreateTime'];
        }
        return false;
    }

    /**
     * 获取接收消息内容正文
     */
    public function getRevContent(){
        if (isset($this->receive['Content']))
            return $this->receive['Content'];
        else if (isset($this->receive['Recognition'])) //获取语音识别文字内容，需申请开通
            return $this->receive['Recognition'];
        else
            return false;
    }

    /**
     * 获取接收消息图片
     */
    public function getRevPic(){
        if (isset($this->receive['PicUrl']))
            return array(
                'mediaid'=>$this->receive['MediaId'],
                'picurl'=>(string)$this->receive['PicUrl'],    //防止picurl为空导致解析出错
            );
        else
            return false;
    }

    /**
     * 获取接收地理位置
     */
    public function getRevGeo(){
        if (isset($this->receive['Location_X'])){
            return array(
                'x'=>$this->receive['Location_X'],
                'y'=>$this->receive['Location_Y'],
                'scale'=>$this->receive['Scale'],
                'label'=>$this->receive['Label']
            );
        } else
            return false;
    }

    /**
     * 获取上报地理位置事件
     */
    public function getRevEventGeo(){
        if (isset($this->receive['Latitude'])){
            return array(
                'x'=>$this->receive['Latitude'],
                'y'=>$this->receive['Longitude'],
                'precision'=>$this->receive['Precision'],
            );
        } else
            return false;
    }

    /**
     * 获取接收事件推送
     */
    public function getRevEvent(){
        if (isset($this->receive['Event'])){
            $array['event'] = $this->receive['Event'];
        }
        if (isset($this->receive['EventKey']) && !empty($this->receive['EventKey'])){
            $array['key'] = $this->receive['EventKey'];
        }
        if (isset($array) && count($array) > 0) {
            return $array;
        } else {
            return false;
        }
    }

    /**
     * 获取自定义菜单的扫码推事件信息
     *
     * 事件类型为以下两种时则调用此方法有效
     * Event	 事件类型，scancode_push
     * Event	 事件类型，scancode_waitmsg
     *
     * @return: array | false
     * array (
     *     'ScanType'=>'qrcode',
     *     'ScanResult'=>'123123'
     * )
     */
    public function getRevScanInfo(){
        if (isset($this->receive['ScanCodeInfo'])){
            if (!is_array($this->receive['ScanCodeInfo'])) {
                $array=(array)$this->receive['ScanCodeInfo'];
                $this->receive['ScanCodeInfo']=$array;
            }else {
                $array=$this->receive['ScanCodeInfo'];
            }
        }
        if (isset($array) && count($array) > 0) {
            return $array;
        } else {
            return false;
        }
    }



    /**
     * 获取自定义菜单的图片发送事件信息
     *
     * 事件类型为以下三种时则调用此方法有效
     * Event	 事件类型，pic_sysphoto        弹出系统拍照发图的事件推送
     * Event	 事件类型，pic_photo_or_album  弹出拍照或者相册发图的事件推送
     * Event	 事件类型，pic_weixin          弹出微信相册发图器的事件推送
     *
     * @return: array | false
     * array (
     *   'Count' => '2',
     *   'PicList' =>array (
     *         'item' =>array (
     *             0 =>array ('PicMd5Sum' => 'aaae42617cf2a14342d96005af53624c'),
     *             1 =>array ('PicMd5Sum' => '149bd39e296860a2adc2f1bb81616ff8'),
     *         ),
     *   ),
     * )
     *
     */
    public function getRevSendPicsInfo(){
        if (isset($this->receive['SendPicsInfo'])){
            if (!is_array($this->receive['SendPicsInfo'])) {
                $array=(array)$this->receive['SendPicsInfo'];
                if (isset($array['PicList'])){
                    $array['PicList']=(array)$array['PicList'];
                    $item=$array['PicList']['item'];
                    $array['PicList']['item']=array();
                    foreach ( $item as $key => $value ){
                        $array['PicList']['item'][$key]=(array)$value;
                    }
                }
                $this->receive['SendPicsInfo']=$array;
            } else {
                $array=$this->receive['SendPicsInfo'];
            }
        }
        if (isset($array) && count($array) > 0) {
            return $array;
        } else {
            return false;
        }
    }

    /**
     * 获取自定义菜单的地理位置选择器事件推送
     *
     * 事件类型为以下时则可以调用此方法有效
     * Event	 事件类型，location_select        弹出系统拍照发图的事件推送
     *
     * @return: array | false
     * array (
     *   'Location_X' => '33.731655000061',
     *   'Location_Y' => '113.29955200008047',
     *   'Scale' => '16',
     *   'Label' => '某某市某某区某某路',
     *   'Poiname' => '',
     * )
     *
     */
    public function getRevSendGeoInfo(){
        if (isset($this->receive['SendLocationInfo'])){
            if (!is_array($this->receive['SendLocationInfo'])) {
                $array=(array)$this->receive['SendLocationInfo'];
                if (empty($array['Poiname'])) {
                    $array['Poiname']="";
                }
                if (empty($array['Label'])) {
                    $array['Label']="";
                }
                $this->receive['SendLocationInfo']=$array;
            } else {
                $array=$this->receive['SendLocationInfo'];
            }
        }
        if (isset($array) && count($array) > 0) {
            return $array;
        } else {
            return false;
        }
    }

    /**
     * 获取接收语音推送
     */
    public function getRevVoice(){
        if (isset($this->receive['MediaId'])){
            return array(
                'mediaid'=>$this->receive['MediaId'],
                'format'=>$this->receive['Format'],
            );
        } else
            return false;
    }


    /**
     * 获取接收视频推送
     */
    public function getRevVideo(){
        if (isset($this->receive['MediaId'])){
            return array(
                'mediaid'=>$this->receive['MediaId'],
                'thumbmediaid'=>$this->receive['ThumbMediaId']
            );
        } else
            return false;
    }

    /**
     * 设置回复消息
     * Example: $obj->text('hello')->reply();
     * @param string $text
     */
    public function text($text = '') {
        $msg = array(
            'ToUserName' => $this->getRevFrom(),
            'FromUserName'=> $this->getRevTo(),
            'MsgType' => self::MSGTYPE_TEXT,
            'Content' => $this->autoTextFilter($text),
            'CreateTime' => time(),
        );
        $this->Message($msg);
        return $this;
    }

    /**
     * 设置回复消息
     * Example: $obj->image('media_id')->reply();
     * @param string $mediaid
     */
    public function image($mediaid='') {
        $msg = array(
            'ToUserName' => $this->getRevFrom(),
            'FromUserName'=>$this->getRevTo(),
            'MsgType'=>self::MSGTYPE_IMAGE,
            'Image'=>array('MediaId'=>$mediaid),
            'CreateTime'=>time(),
        );
        $this->Message($msg);
        return $this;
    }

    /**
     * 设置回复消息
     * Example: $obj->voice('media_id')->reply();
     * @param string $mediaid
     */
    public function voice($mediaid='') {
        $msg = array(
            'ToUserName' => $this->getRevFrom(),
            'FromUserName'=>$this->getRevTo(),
            'MsgType'=>self::MSGTYPE_IMAGE,
            'Voice'=>array('MediaId'=>$mediaid),
            'CreateTime'=>time(),
        );
        $this->Message($msg);
        return $this;
    }

    /**
     * 设置回复消息
     * Example: $obj->video('media_id','title','description')->reply();
     * @param string $mediaid
     */
    public function video($mediaid='',$title='',$description='') {
        $msg = array(
            'ToUserName' => $this->getRevFrom(),
            'FromUserName'=>$this->getRevTo(),
            'MsgType'=>self::MSGTYPE_IMAGE,
            'Video'=>array(
                'MediaId'=>$mediaid,
                'Title'=>$title,
                'Description'=>$description
            ),
            'CreateTime'=>time(),
        );
        $this->Message($msg);
        return $this;
    }

    /**
     * 设置回复图文
     * @param array $newsData
     * 数组结构:
     *  array(
     *  	"0"=>array(
     *  		'Title'=>'msg title',
     *  		'Description'=>'summary text',
     *  		'PicUrl'=>'http://www.domain.com/1.jpg',
     *  		'Url'=>'http://www.domain.com/1.html'
     *  	),
     *  	"1"=>....
     *  )
     */
    public function news($newsData=array()) {
        $count = count($newsData);
        $msg = array(
            'ToUserName' => $this->getRevFrom(),
            'FromUserName'=>$this->getRevTo(),
            'MsgType'=>self::MSGTYPE_NEWS,
            'CreateTime'=>time(),
            'ArticleCount'=>$count,
            'Articles'=>$newsData,

        );
        $this->Message($msg);
        return $this;
    }

    /**
     *
     * 回复微信服务器, 此函数支持链式操作
     * Example: $this->text('msg tips')->reply();
     * @param string $msg 要发送的信息, 默认取$this->msg
     * @param bool $return 是否返回信息而不抛出到浏览器 默认:否
     */
    public function reply($msg=array(),$return = false)
    {
        if (empty($msg)) {
            if (empty($this->msg))   //防止不先设置回复内容，直接调用reply方法导致异常
                return false;
            $msg = $this->msg;
        }
        $xmldata=  $this->xmlEncode($msg);
        $this->log($xmldata);
        if ($this->encryptType == 'aes') { //如果来源消息为加密方式
            $pc = new Prpcrypt($this->encodingAesKey);
            $array = $pc->encrypt($xmldata, $this->appId);
            $ret = $array[0];
            if ($ret != 0) {
                $this->log('encrypt err!');
                return false;
            }
            $timestamp = time();
            $nonce = rand(77,999)*rand(605,888)*rand(11,99);
            $encrypt = $array[1];
            $tmpArr = array($this->token, $timestamp, $nonce,$encrypt);//比普通公众平台多了一个加密的密文
            sort($tmpArr, SORT_STRING);
            $signature = implode($tmpArr);
            $signature = sha1($signature);
            $xmldata = $this->generate($encrypt, $signature, $timestamp, $nonce);
            $this->log($xmldata);
        }
        if (!$return)
            echo $xmldata;
        return $xmldata;
    }

    protected function generate($encrypt, $signature, $timestamp, $nonce) {
        //格式化加密信息
        $format = "<xml>
            <Encrypt><![CDATA[%s]]></Encrypt>
            <MsgSignature><![CDATA[%s]]></MsgSignature>
            <TimeStamp>%s</TimeStamp>
            <Nonce><![CDATA[%s]]></Nonce>
            </xml>";
        return sprintf($format, $encrypt, $signature, $timestamp, $nonce);
    }

    /**
     * 设置缓存，按需重载
     * @param string $cachename
     * @param mixed $value
     * @param int $expired
     * @return boolean
     */
    protected function setCache($cachename,$value,$expired){
        return false;
    }

    /**
     * 获取缓存，按需重载
     * @param string $cachename
     * @return mixed
     */
    protected function getCache($cachename){
        return false;
    }

    /**
     * 清除缓存，按需重载
     * @param string $cachename
     * @return boolean
     */
    protected function removeCache($cachename){
        return false;
    }

    /**
     * 删除验证数据
     * @param string $appid
     */
    public function resetAuth($appid=''){
        if (!$appid) $appid = $this->appId;
        $this->accessToken = '';
        $authname = 'wechat_access_token'.$appid;
        $this->removeCache($authname);
        return true;
    }

    /**
     * 删除JSAPI授权TICKET
     * @param string $appid 用于多个appid时使用
     */
    public function resetJsTicket($appid=''){
        if (!$appid) $appid = $this->appId;
        $this->jsApiTicket = '';
        $authname = 'wechat_jsapi_ticket'.$appid;
        $this->removeCache($authname);
        return true;
    }


    abstract public function getJsTicket($appid = '', $jsapi_ticket = '');

    /**
     * 获取JsApi使用签名
     * @param string $url 网页的URL，自动处理#及其后面部分
     * @param string $timestamp 当前时间戳 (为空则自动生成)
     * @param string $noncestr 随机串 (为空则自动生成)
     * @param string $appid 用于多个appid时使用,可空
     * @return array|bool 返回签名字串
     */
    public function getJsSign($url, $timestamp=0, $noncestr='', $appid=''){
        if (!$this->jsApiTicket && !$this->getJsTicket($appid) || !$url) return false;
        if (!$timestamp)
            $timestamp = time();
        if (!$noncestr)
            $noncestr = $this->generateNonceStr();
        $ret = strpos($url,'#');
        if ($ret)
            $url = substr($url,0,$ret);
        $url = trim($url);
        if (empty($url))
            return false;
        $arrdata = array("timestamp" => $timestamp, "noncestr" => $noncestr, "url" => $url, "jsapi_ticket" => $this->jsApiTicket);
        $sign = $this->getSignature($arrdata);
        if (!$sign)
            return false;
        $signPackage = array(
            "appId"     => $this->appId,
            "nonceStr"  => $noncestr,
            "timestamp" => $timestamp,
            "url"       => $url,
            "signature" => $sign
        );
        return $signPackage;
    }

    /**
     * 获取签名
     * @param array $arrdata 签名数组
     * @param string $method 签名方法
     * @return boolean|string 签名值
     */
    public function getSignature($arrdata,$method="sha1") {
        if (!function_exists($method)) return false;
        ksort($arrdata);
        $paramstring = "";
        foreach($arrdata as $key => $value)
        {
            if(strlen($paramstring) == 0)
                $paramstring .= $key . "=" . $value;
            else
                $paramstring .= "&" . $key . "=" . $value;
        }
        $Sign = $method($paramstring);
        return $Sign;
    }

    /**
     * 生成随机字串
     * @param number $length 长度，默认为16，最长为32字节
     * @return string
     */
    public function generateNonceStr($length=16){
        // 密码字符集，可任意添加你需要的字符
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str = "";
        for($i = 0; $i < $length; $i++)
        {
            $str .= $chars[mt_rand(0, strlen($chars) - 1)];
        }
        return $str;
    }

    /**
     * 上传临时素材，有效期为3天(认证后的订阅号可用)
     * 注意：上传大文件时可能需要先调用 set_time_limit(0) 避免超时
     * 注意：数组的键值任意，但文件名前必须加@，使用单引号以避免本地路径斜杠被转义
     * 注意：临时素材的media_id是可复用的！
     * @param array $data {"media":'@Path\filename.jpg'}
     * @param string $type 类型：图片:image 语音:voice 视频:video 缩略图:thumb
     * @return boolean|array
     */
    public function uploadMedia($data, $type){
        if (!$this->accessToken && !$this->checkAuth()) return false;
        //原先的上传多媒体文件接口使用 self::UPLOAD_MEDIA_URL 前缀
        $result = $this->httpPost(self::API_URL_PREFIX.self::MEDIA_UPLOAD_URL.'access_token='.$this->accessToken.'&type='.$type,$data,true);
        if ($result)
        {
            $json = json_decode($result,true);
            if (!$json || !empty($json['errcode'])) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                return false;
            }
            return $json;
        }
        return false;
    }

    /**
     * 获取临时素材(认证后的订阅号可用)
     * @param string $media_id 媒体文件id
     * @param boolean $is_video 是否为视频文件，默认为否
     * @return boolean|array data
     */
    public function getMedia($media_id,$is_video=false){
        if (!$this->accessToken && !$this->checkAuth()) return false;
        //原先的上传多媒体文件接口使用 self::UPLOAD_MEDIA_URL 前缀
        //如果要获取的素材是视频文件时，不能使用https协议，必须更换成http协议
        $url_prefix = $is_video?str_replace('https','http',self::API_URL_PREFIX):self::API_URL_PREFIX;
        $result = $this->httpGet($url_prefix.self::MEDIA_GET_URL.'access_token='.$this->accessToken.'&media_id='.$media_id);
        if ($result)
        {
            if (is_string($result)) {
                $json = json_decode($result,true);
                if (isset($json['errcode'])) {
                    $this->errCode = $json['errcode'];
                    $this->errMsg = $json['errmsg'];
                    return false;
                }
            }
            return $result;
        }
        return false;
    }

    /**
     * 获取微信服务器IP地址列表
     * @return array('127.0.0.1','127.0.0.1')
     */
    public function getServerIp(){
        if (!$this->accessToken && !$this->checkAuth()) return false;
        $result = $this->httpGet(self::API_URL_PREFIX.self::CALLBACKSERVER_GET_URL.'access_token='.$this->accessToken);
        if ($result)
        {
            $json = json_decode($result,true);
            if (!$json || isset($json['errcode'])) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                return false;
            }
            return $json['ip_list'];
        }
        return false;
    }

    /**
     * 获取关注者详细信息
     * @param string $openid
     * @return array {subscribe,openid,nickname,sex,city,province,country,language,headimgurl,subscribe_time,[unionid]}
     * 注意：unionid字段 只有在用户将公众号绑定到微信开放平台账号后，才会出现。建议调用前用isset()检测一下
     * {
     *    "errcode": 0,
     *    "errmsg": "ok",
     *    "userid": "zhangsan",
     *    "name": "李四",
     *    "department": [1, 2],
     *    "position": "后台工程师",
     *    "mobile": "15913215421",
     *    "gender": 1,     //性别。gender=0表示男，=1表示女
     *    "tel": "62394",
     *    "email": "zhangsan@gzdev.com",
     *    "weixinid": "lisifordev",        //微信号
     *    "avatar": "http://wx.qlogo.cn/mmopen/ajNVdqHZLLA3W..../0",   //头像url。注：如果要获取小图将url最后的"/0"改成"/64"即可
     *    "status": 1      //关注状态: 1=已关注，2=已冻结，4=未关注
     *    "extattr": {"attrs":[{"name":"爱好","value":"旅游"},{"name":"卡号","value":"1234567234"}]}
     * }
     */
    public function getUserInfo($openid){
        if (!$this->accessToken && !$this->checkAuth()) return false;
        $result = $this->httpGet(self::API_URL_PREFIX.self::USER_INFO_URL.'access_token='.$this->accessToken.'&openid='.$openid);
        if ($result)
        {
            $json = json_decode($result,true);
            if (isset($json['errcode'])) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                return false;
            }
            return $json;
        }
        return false;
    }

    abstract public function checkAuth();

    abstract public function createMenu($data);

    abstract public function getMenu();

    abstract public function deleteMenu();


    /**
     * oauth 授权跳转接口
     * @param string $callback 回调URI
     * @param string $state 重定向后会带上state参数，企业可以填写a-zA-Z0-9的参数值
     * @return string
     */
    public function getOauthRedirect($callback,$state='',$scope='snsapi_userinfo'){
        return self::OAUTH_PREFIX.self::OAUTH_AUTHORIZE_URL.'appid='.$this->appId.'&redirect_uri='.urlencode($callback).'&response_type=code&scope='.$scope.'&state='.$state.'#wechat_redirect';
    }
}