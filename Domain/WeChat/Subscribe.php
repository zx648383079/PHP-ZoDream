<?php
namespace Domain\WeChat;
/**
 * 订阅号
 * Created by PhpStorm.
 * User: zx648
 * Date: 2016/3/9
 * Time: 10:43
 */
class Subscribe extends Core {

    const EVENT_SCAN = 'SCAN';                 //扫描带参数二维码
    const EVENT_KF_SEESION_CREATE = 'kfcreatesession';  //多客服 - 接入会话
    const EVENT_KF_SEESION_CLOSE = 'kfclosesession';    //多客服 - 关闭会话
    const EVENT_KF_SEESION_SWITCH = 'kfswitchsession';  //多客服 - 转接会话
    const EVENT_CARD_PASS = 'card_pass_check';          //卡券 - 审核通过
    const EVENT_CARD_NOTPASS = 'card_not_pass_check';   //卡券 - 审核未通过
    const EVENT_CARD_USER_GET = 'user_get_card';        //卡券 - 用户领取卡券
    const EVENT_CARD_USER_DEL = 'user_del_card';        //卡券 - 用户删除卡券
    const EVENT_MERCHANT_ORDER = 'merchant_order';        //微信小店 - 订单付款通知
    const API_URL_PREFIX = 'https://api.weixin.qq.com/cgi-bin';
    const AUTH_URL = '/token?grant_type=client_credential&';
    const GET_TICKET_URL = '/ticket/getticket?';
    const QRCODE_CREATE_URL='/qrcode/create?';
    const QR_SCENE = 0;
    const QR_LIMIT_SCENE = 1;
    const QRCODE_IMG_URL='https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=';
    const SHORT_URL='/shorturl?';
    const USER_INFO_URL='/user/info?';
    const USER_UPDATEREMARK_URL='/user/info/updateremark?';
    const GROUP_GET_URL='/groups/get?';
    const USER_GROUP_URL='/groups/getid?';
    const GROUP_CREATE_URL='/groups/create?';
    const GROUP_UPDATE_URL='/groups/update?';
    const GROUP_MEMBER_UPDATE_URL='/groups/members/update?';
    const GROUP_MEMBER_BATCHUPDATE_URL='/groups/members/batchupdate?';
    const CUSTOM_SEND_URL='/message/custom/send?';
    const MEDIA_UPLOADNEWS_URL = '/media/uploadnews?';
    const MASS_SEND_URL = '/message/mass/send?';
    const TEMPLATE_SET_INDUSTRY_URL = '/message/template/api_set_industry?';
    const TEMPLATE_ADD_TPL_URL = '/message/template/api_add_template?';
    const TEMPLATE_SEND_URL = '/message/template/send?';
    const MASS_SEND_GROUP_URL = '/message/mass/sendall?';
    const MASS_DELETE_URL = '/message/mass/delete?';
    const MASS_PREVIEW_URL = '/message/mass/preview?';
    const MASS_QUERY_URL = '/message/mass/get?';
    const UPLOAD_MEDIA_URL = 'http://file.api.weixin.qq.com/cgi-bin';
    const MEDIA_UPLOADIMG_URL = '/media/uploadimg?';//图片上传接口
    const MEDIA_VIDEO_UPLOAD = '/media/uploadvideo?';
    const MEDIA_FOREVER_UPLOAD_URL = '/material/add_material?';
    const MEDIA_FOREVER_NEWS_UPLOAD_URL = '/material/add_news?';
    const MEDIA_FOREVER_NEWS_UPDATE_URL = '/material/update_news?';
    const MEDIA_FOREVER_GET_URL = '/material/get_material?';
    const MEDIA_FOREVER_DEL_URL = '/material/del_material?';
    const MEDIA_FOREVER_COUNT_URL = '/material/get_materialcount?';
    const MEDIA_FOREVER_BATCHGET_URL = '/material/batchget_material?';
    ///多客服相关地址
    const CUSTOM_SERVICE_GET_RECORD = '/customservice/getrecord?';
    const CUSTOM_SERVICE_GET_KFLIST = '/customservice/getkflist?';
    const CUSTOM_SERVICE_GET_ONLINEKFLIST = '/customservice/getonlinekflist?';
    const API_BASE_URL_PREFIX = 'https://api.weixin.qq.com'; //以下API接口URL需要使用此前缀
    const OAUTH_TOKEN_URL = '/sns/oauth2/access_token?';
    const OAUTH_REFRESH_URL = '/sns/oauth2/refresh_token?';
    const OAUTH_USERINFO_URL = '/sns/userinfo?';
    const OAUTH_AUTH_URL = '/sns/auth?';
    ///多客服相关地址
    const CUSTOM_SESSION_CREATE = '/customservice/kfsession/create?';
    const CUSTOM_SESSION_CLOSE = '/customservice/kfsession/close?';
    const CUSTOM_SESSION_SWITCH = '/customservice/kfsession/switch?';
    const CUSTOM_SESSION_GET = '/customservice/kfsession/getsession?';
    const CUSTOM_SESSION_GET_LIST = '/customservice/kfsession/getsessionlist?';
    const CUSTOM_SESSION_GET_WAIT = '/customservice/kfsession/getwaitcase?';
    const CS_KF_ACCOUNT_ADD_URL = '/customservice/kfaccount/add?';
    const CS_KF_ACCOUNT_UPDATE_URL = '/customservice/kfaccount/update?';
    const CS_KF_ACCOUNT_DEL_URL = '/customservice/kfaccount/del?';
    const CS_KF_ACCOUNT_UPLOAD_HEADIMG_URL = '/customservice/kfaccount/uploadheadimg?';
    ///卡券相关地址
    const CARD_CREATE                     = '/card/create?';
    const CARD_DELETE                     = '/card/delete?';
    const CARD_UPDATE                     = '/card/update?';
    const CARD_GET                        = '/card/get?';
    const CARD_BATCHGET                   = '/card/batchget?';
    const CARD_MODIFY_STOCK               = '/card/modifystock?';
    const CARD_LOCATION_BATCHADD          = '/card/location/batchadd?';
    const CARD_LOCATION_BATCHGET          = '/card/location/batchget?';
    const CARD_GETCOLORS                  = '/card/getcolors?';
    const CARD_QRCODE_CREATE              = '/card/qrcode/create?';
    const CARD_CODE_CONSUME               = '/card/code/consume?';
    const CARD_CODE_DECRYPT               = '/card/code/decrypt?';
    const CARD_CODE_GET                   = '/card/code/get?';
    const CARD_CODE_UPDATE                = '/card/code/update?';
    const CARD_CODE_UNAVAILABLE           = '/card/code/unavailable?';
    const CARD_TESTWHILELIST_SET          = '/card/testwhitelist/set?';
    const CARD_MEETINGCARD_UPDATEUSER      = '/card/meetingticket/updateuser?';    //更新会议门票
    const CARD_MEMBERCARD_ACTIVATE        = '/card/membercard/activate?';      //激活会员卡
    const CARD_MEMBERCARD_UPDATEUSER      = '/card/membercard/updateuser?';    //更新会员卡
    const CARD_MOVIETICKET_UPDATEUSER     = '/card/movieticket/updateuser?';   //更新电影票(未加方法)
    const CARD_BOARDINGPASS_CHECKIN       = '/card/boardingpass/checkin?';     //飞机票-在线选座(未加方法)
    const CARD_LUCKYMONEY_UPDATE          = '/card/luckymoney/updateuserbalance?';     //更新红包金额
    const SEMANTIC_API_URL = '/semantic/semproxy/search?'; //语义理解
    ///数据分析接口
    static $DATACUBE_URL_ARR = array(        //用户分析
        'user' => array(
            'summary' => '/datacube/getusersummary?',		//获取用户增减数据（getusersummary）
            'cumulate' => '/datacube/getusercumulate?',		//获取累计用户数据（getusercumulate）
        ),
        'article' => array(            //图文分析
            'summary' => '/datacube/getarticlesummary?',		//获取图文群发每日数据（getarticlesummary）
            'total' => '/datacube/getarticletotal?',		//获取图文群发总数据（getarticletotal）
            'read' => '/datacube/getuserread?',			//获取图文统计数据（getuserread）
            'readhour' => '/datacube/getuserreadhour?',		//获取图文统计分时数据（getuserreadhour）
            'share' => '/datacube/getusershare?',			//获取图文分享转发数据（getusershare）
            'sharehour' => '/datacube/getusersharehour?',		//获取图文分享转发分时数据（getusersharehour）
        ),
        'upstreammsg' => array(        //消息分析
            'summary' => '/datacube/getupstreammsg?',		//获取消息发送概况数据（getupstreammsg）
            'hour' => '/datacube/getupstreammsghour?',	//获取消息分送分时数据（getupstreammsghour）
            'week' => '/datacube/getupstreammsgweek?',	//获取消息发送周数据（getupstreammsgweek）
            'month' => '/datacube/getupstreammsgmonth?',	//获取消息发送月数据（getupstreammsgmonth）
            'dist' => '/datacube/getupstreammsgdist?',	//获取消息发送分布数据（getupstreammsgdist）
            'distweek' => '/datacube/getupstreammsgdistweek?',	//获取消息发送分布周数据（getupstreammsgdistweek）
            'distmonth' => '/datacube/getupstreammsgdistmonth?',	//获取消息发送分布月数据（getupstreammsgdistmonth）
        ),
        'interface' => array(        //接口分析
            'summary' => '/datacube/getinterfacesummary?',	//获取接口分析数据（getinterfacesummary）
            'summaryhour' => '/datacube/getinterfacesummaryhour?',	//获取接口分析分时数据（getinterfacesummaryhour）
        )
    );
    ///微信摇一摇周边
    const SHAKEAROUND_DEVICE_APPLYID = '/shakearound/device/applyid?';//申请设备ID
    const SHAKEAROUND_DEVICE_UPDATE = '/shakearound/device/update?';//编辑设备信息
    const SHAKEAROUND_DEVICE_SEARCH = '/shakearound/device/search?';//查询设备列表
    const SHAKEAROUND_DEVICE_BINDLOCATION = '/shakearound/device/bindlocation?';//配置设备与门店ID的关系
    const SHAKEAROUND_DEVICE_BINDPAGE = '/shakearound/device/bindpage?';//配置设备与页面的绑定关系
    const SHAKEAROUND_MATERIAL_ADD = '/shakearound/material/add?';//上传摇一摇图片素材
    const SHAKEAROUND_PAGE_ADD = '/shakearound/page/add?';//增加页面
    const SHAKEAROUND_PAGE_UPDATE = '/shakearound/page/update?';//编辑页面
    const SHAKEAROUND_PAGE_SEARCH = '/shakearound/page/search?';//查询页面列表
    const SHAKEAROUND_PAGE_DELETE = '/shakearound/page/delete?';//删除页面
    const SHAKEAROUND_USER_GETSHAKEINFO = '/shakearound/user/getshakeinfo?';//获取摇周边的设备及用户信息
    const SHAKEAROUND_STATISTICS_DEVICE = '/shakearound/statistics/device?';//以设备为维度的数据统计接口
    const SHAKEAROUND_STATISTICS_PAGE = '/shakearound/statistics/page?';//以页面为维度的数据统计接口
    ///微信小店相关接口
    const MERCHANT_ORDER_GETBYID = '/merchant/order/getbyid?';//根据订单ID获取订单详情
    const MERCHANT_ORDER_GETBYFILTER = '/merchant/order/getbyfilter?';//根据订单状态/创建时间获取订单详情
    const MERCHANT_ORDER_SETDELIVERY = '/merchant/order/setdelivery?';//设置订单发货信息
    const MERCHANT_ORDER_CLOSE = '/merchant/order/close?';//关闭订单
    
    protected $apiTicket;
    protected $userToken;
    protected $partnerId;
    protected $partnerKey;
    protected $paySignKey;


    /**
     * 获取接收消息链接
     */
    public function getRevLink(){
        if (isset($this->receive['Url'])){
            return array(
                'url'=>$this->receive['Url'],
                'title'=>$this->receive['Title'],
                'description'=>$this->receive['Description']
            );
        } else
            return false;
    }


    /**
     * 获取接收TICKET
     */
    public function getRevTicket(){
        if (isset($this->receive['Ticket'])){
            return $this->receive['Ticket'];
        } else
            return false;
    }

    /**
     * 获取二维码的场景值
     */
    public function getRevSceneId (){
        if (isset($this->receive['EventKey'])){
            return str_replace('qrscene_','',$this->receive['EventKey']);
        } else{
            return false;
        }
    }

    /**
     * 获取主动推送的消息ID
     * 经过验证，这个和普通的消息MsgId不一样
     * 当Event为 MASSSENDJOBFINISH 或 TEMPLATESENDJOBFINISH
     */
    public function getRevTplMsgID(){
        if (isset($this->receive['MsgID'])){
            return $this->receive['MsgID'];
        } else
            return false;
    }

    /**
     * 获取模板消息发送状态
     */
    public function getRevStatus(){
        if (isset($this->receive['Status'])){
            return $this->receive['Status'];
        } else
            return false;
    }

    /**
     * 获取群发或模板消息发送结果
     * 当Event为 MASSSENDJOBFINISH 或 TEMPLATESENDJOBFINISH，即高级群发/模板消息
     */
    public function getRevResult(){
        if (isset($this->receive['Status'])) //发送是否成功，具体的返回值请参考 高级群发/模板消息 的事件推送说明
            $array['Status'] = $this->receive['Status'];
        if (isset($this->receive['MsgID'])) //发送的消息id
            $array['MsgID'] = $this->receive['MsgID'];

        //以下仅当群发消息时才会有的事件内容
        if (isset($this->receive['TotalCount']))     //分组或openid列表内粉丝数量
            $array['TotalCount'] = $this->receive['TotalCount'];
        if (isset($this->receive['FilterCount']))    //过滤（过滤是指特定地区、性别的过滤、用户设置拒收的过滤，用户接收已超4条的过滤）后，准备发送的粉丝数
            $array['FilterCount'] = $this->receive['FilterCount'];
        if (isset($this->receive['SentCount']))     //发送成功的粉丝数
            $array['SentCount'] = $this->receive['SentCount'];
        if (isset($this->receive['ErrorCount']))    //发送失败的粉丝数
            $array['ErrorCount'] = $this->receive['ErrorCount'];
        if (isset($array) && count($array) > 0) {
            return $array;
        } else {
            return false;
        }
    }

    /**
     * 获取多客服会话状态推送事件 - 接入会话
     * 当Event为 kfcreatesession 即接入会话
     * @return string | boolean  返回分配到的客服
     */
    public function getRevKFCreate(){
        if (isset($this->receive['KfAccount'])){
            return $this->receive['KfAccount'];
        } else
            return false;
    }

    /**
     * 获取多客服会话状态推送事件 - 关闭会话
     * 当Event为 kfclosesession 即关闭会话
     * @return string | boolean  返回分配到的客服
     */
    public function getRevKFClose(){
        if (isset($this->receive['KfAccount'])){
            return $this->receive['KfAccount'];
        } else
            return false;
    }

    /**
     * 获取多客服会话状态推送事件 - 转接会话
     * 当Event为 kfswitchsession 即转接会话
     * @return array | boolean  返回分配到的客服
     * {
     *     'FromKfAccount' => '',      //原接入客服
     *     'ToKfAccount' => ''            //转接到客服
     * }
     */
    public function getRevKFSwitch(){
        if (isset($this->receive['FromKfAccount']))     //原接入客服
            $array['FromKfAccount'] = $this->receive['FromKfAccount'];
        if (isset($this->receive['ToKfAccount']))    //转接到客服
            $array['ToKfAccount'] = $this->receive['ToKfAccount'];
        if (isset($array) && count($array) > 0) {
            return $array;
        } else {
            return false;
        }
    }

    /**
     * 获取卡券事件推送 - 卡卷审核是否通过
     * 当Event为 card_pass_check(审核通过) 或 card_not_pass_check(未通过)
     * @return string|boolean  返回卡券ID
     */
    public function getRevCardPass(){
        if (isset($this->receive['CardId']))
            return $this->receive['CardId'];
        else
            return false;
    }

    /**
     * 获取卡券事件推送 - 领取卡券
     * 当Event为 user_get_card(用户领取卡券)
     * @return array|boolean
     */
    public function getRevCardGet(){
        if (isset($this->receive['CardId']))     //卡券 ID
            $array['CardId'] = $this->receive['CardId'];
        if (isset($this->receive['IsGiveByFriend']))    //是否为转赠，1 代表是，0 代表否。
            $array['IsGiveByFriend'] = $this->receive['IsGiveByFriend'];
        $array['OldUserCardCode'] = $this->receive['OldUserCardCode'];
        if (isset($this->receive['UserCardCode']) && !empty($this->receive['UserCardCode'])) //code 序列号。自定义 code 及非自定义 code的卡券被领取后都支持事件推送。
            $array['UserCardCode'] = $this->receive['UserCardCode'];
        if (isset($array) && count($array) > 0) {
            return $array;
        } else {
            return false;
        }
    }

    /**
     * 获取卡券事件推送 - 删除卡券
     * 当Event为 user_del_card(用户删除卡券)
     * @return array|boolean
     */
    public function getRevCardDel(){
        if (isset($this->receive['CardId']))     //卡券 ID
            $array['CardId'] = $this->receive['CardId'];
        if (isset($this->receive['UserCardCode']) && !empty($this->receive['UserCardCode'])) //code 序列号。自定义 code 及非自定义 code的卡券被领取后都支持事件推送。
            $array['UserCardCode'] = $this->receive['UserCardCode'];
        if (isset($array) && count($array) > 0) {
            return $array;
        } else {
            return false;
        }
    }

    /**
     * 获取订单ID - 订单付款通知
     * 当Event为 merchant_order(订单付款通知)
     * @return string|boolean
     */
    public function getRevOrderId(){
        if (isset($this->receive['OrderId']))     //订单 ID
            return $this->receive['OrderId'];
        else
            return false;
    }

    /**
     * 设置回复音乐
     * @param string $title
     * @param string $desc
     * @param string $musicUrl
     * @param string $hgMusicUrl
     * @param string $thumbMediaId 音乐图片缩略图的媒体id，非必须
     * @return $this
     */
    public function music($title, $desc, $musicUrl, $hgMusicUrl = '',$thumbMediaId = '') {
        $FuncFlag = $this->funcFlag ? 1 : 0;
        $msg = array(
            'ToUserName' => $this->getRevFrom(),
            'FromUserName'=>$this->getRevTo(),
            'CreateTime'=>time(),
            'MsgType'=>self::MSGTYPE_MUSIC,
            'Music'=>array(
                'Title'=>$title,
                'Description'=>$desc,
                'MusicUrl'=>$musicUrl,
                'HQMusicUrl'=>$hgMusicUrl
            ),
            'FuncFlag'=>$FuncFlag
        );
        if ($thumbMediaId) {
            $msg['Music']['ThumbMediaId'] = $thumbMediaId;
        }
        $this->Message($msg);
        return $this;
    }

    /**
     * 获取access_token
     * @param string $appId 如在类初始化时已提供，则可为空
     * @param string $appSecret 如在类初始化时已提供，则可为空
     * @param string $token 手动指定access_token，非必要情况不建议用
     * @return bool|mixed
     */
    public function checkAuth($appId='', $appSecret='', $token=''){
        if (!$appId || !$appSecret) {
            $appId = $this->appId;
            $appSecret = $this->appSecret;
        }
        if ($token) { //手动指定token，优先使用
            $this->accessToken=$token;
            return $this->accessToken;
        }

        $authName = 'wechat_access_token'.$appId;
        if ($rs = $this->getCache($authName))  {
            $this->accessToken = $rs;
            return $rs;
        }

        $result = $this->httpGet(self::API_URL_PREFIX.self::AUTH_URL.'appid='.$appId.'&secret='.$appSecret);
        if ($result)
        {
            $json = json_decode($result,true);
            if (!$json || isset($json['errcode'])) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                return false;
            }
            $this->accessToken = $json['access_token'];
            $expire = $json['expires_in'] ? intval($json['expires_in'])-100 : 3600;
            $this->setCache($authName,$this->accessToken,$expire);
            return $this->accessToken;
        }
        return false;
    }


    /**
     * 获取JSAPI授权TICKET
     * @param string $appId 用于多个appid时使用,可空
     * @param string $jsApiTicket 手动指定jsapi_ticket，非必要情况不建议用
     *
     * @return bool|mixed
     */
    public function getJsTicket($appId = '', $jsApiTicket = ''){
        if (!$this->accessToken && !$this->checkAuth()) return false;
        if (!$appId) $appId = $this->appId;
        if ($jsApiTicket) { //手动指定token，优先使用
            $this->jsApiTicket = $jsApiTicket;
            return $this->jsApiTicket;
        }
        $authName = 'wechat_jsapi_ticket'.$appId;
        if ($rs = $this->getCache($authName))  {
            $this->jsApiTicket = $rs;
            return $rs;
        }
        $result = $this->httpGet(self::API_URL_PREFIX.self::GET_TICKET_URL.'access_token='.$this->accessToken.'&type=jsapi');
        if ($result)
        {
            $json = json_decode($result,true);
            if (!$json || !empty($json['errcode'])) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                return false;
            }
            $this->jsApiTicket = $json['ticket'];
            $expire = $json['expires_in'] ? intval($json['expires_in'])-100 : 3600;
            $this->setCache($authName,$this->jsApiTicket,$expire);
            return $this->jsApiTicket;
        }
        return false;
    }


    /**
     * 获取微信卡券api_ticket
     * @param string $appId 用于多个appid时使用,可空
     * @param string $api_ticket 手动指定api_ticket，非必要情况不建议用
     *
     * @return bool|mixed
     */
    public function getJsCardTicket($appId = '', $api_ticket = ''){
        if (!$this->accessToken && !$this->checkAuth()) return false;
        if (!$appId) $appId = $this->appId;
        if ($api_ticket) { //手动指定token，优先使用
            $this->apiTicket = $api_ticket;
            return $this->apiTicket;
        }
        $authName = 'wechat_api_ticket_wxcard'.$appId;
        if ($rs = $this->getCache($authName))  {
            $this->apiTicket = $rs;
            return $rs;
        }
        $result = $this->httpGet(self::API_URL_PREFIX.self::GET_TICKET_URL.'access_token='.$this->accessToken.'&type=wx_card');
        if ($result)
        {
            $json = json_decode($result,true);
            if (!$json || !empty($json['errcode'])) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                return false;
            }
            $this->apiTicket = $json['ticket'];
            $expire = $json['expires_in'] ? intval($json['expires_in'])-100 : 3600;
            $this->setCache($authName,$this->apiTicket,$expire);
            return $this->apiTicket;
        }
        return false;
    }

    /**
     * 获取微信卡券签名
     * @param array $arrData 签名数组
     * @param string $method 签名方法
     * @return boolean|string 签名值
     */
    public function getTicketSignature($arrData, $method = 'sha1') {
        if (!function_exists($method)) return false;
        $newArray = array();
        foreach($arrData as $key => $value)
        {
            array_push($newArray,(string)$value);
        }
        sort($newArray,SORT_STRING);
        return $method(implode($newArray));
    }


    /**
     * 创建菜单(认证后的订阅号可用)
     * @param array $data 菜单数组数据
     * example:
     *    array (
     *        'button' => array (
     *          0 => array (
     *            'name' => '扫码',
     *            'sub_button' => array (
     *                0 => array (
     *                  'type' => 'scancode_waitmsg',
     *                  'name' => '扫码带提示',
     *                  'key' => 'rselfmenu_0_0',
     *                ),
     *                1 => array (
     *                  'type' => 'scancode_push',
     *                  'name' => '扫码推事件',
     *                  'key' => 'rselfmenu_0_1',
     *                ),
     *            ),
     *          ),
     *          1 => array (
     *            'name' => '发图',
     *            'sub_button' => array (
     *                0 => array (
     *                  'type' => 'pic_sysphoto',
     *                  'name' => '系统拍照发图',
     *                  'key' => 'rselfmenu_1_0',
     *                ),
     *                1 => array (
     *                  'type' => 'pic_photo_or_album',
     *                  'name' => '拍照或者相册发图',
     *                  'key' => 'rselfmenu_1_1',
     *                )
     *            ),
     *          ),
     *          2 => array (
     *            'type' => 'location_select',
     *            'name' => '发送位置',
     *            'key' => 'rselfmenu_2_0'
     *          ),
     *        ),
     *    )
     * type可以选择为以下几种，其中5-8除了收到菜单事件以外，还会单独收到对应类型的信息。
     * 1、click：点击推事件
     * 2、view：跳转URL
     * 3、scancode_push：扫码推事件
     * 4、scancode_waitmsg：扫码推事件且弹出“消息接收中”提示框
     * 5、pic_sysphoto：弹出系统拍照发图
     * 6、pic_photo_or_album：弹出拍照或者相册发图
     * 7、pic_weixin：弹出微信相册发图器
     * 8、location_select：弹出地理位置选择器
     * @return bool
     */
    public function createMenu($data){
        if (!$this->accessToken && !$this->checkAuth()) return false;
        $result = $this->httpPost(self::API_URL_PREFIX.self::MENU_CREATE_URL.'access_token='.$this->accessToken,self::jsonEncode($data));
        if ($result)
        {
            $json = json_decode($result,true);
            if (!$json || !empty($json['errcode'])) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                return false;
            }
            return true;
        }
        return false;
    }

    /**
     * 获取菜单(认证后的订阅号可用)
     * @return array('menu'=>array(....s))
     */
    public function getMenu(){
        if (!$this->accessToken && !$this->checkAuth()) return false;
        $result = $this->httpGet(self::API_URL_PREFIX.self::MENU_GET_URL.'access_token='.$this->accessToken);
        if ($result)
        {
            $json = json_decode($result,true);
            if (!$json || isset($json['errcode'])) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                return false;
            }
            return $json;
        }
        return false;
    }

    /**
     * 删除菜单(认证后的订阅号可用)
     * @return boolean
     */
    public function deleteMenu(){
        if (!$this->accessToken && !$this->checkAuth()) return false;
        $result = $this->httpGet(self::API_URL_PREFIX.self::MENU_DELETE_URL.'access_token='.$this->accessToken);
        if ($result)
        {
            $json = json_decode($result,true);
            if (!$json || !empty($json['errcode'])) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                return false;
            }
            return true;
        }
        return false;
    }





    /**
     * 上传图片，本接口所上传的图片不占用公众号的素材库中图片数量的5000个的限制。图片仅支持jpg/png格式，大小必须在1MB以下。 (认证后的订阅号可用)
     * 注意：上传大文件时可能需要先调用 set_time_limit(0) 避免超时
     * 注意：数组的键值任意，但文件名前必须加@，使用单引号以避免本地路径斜杠被转义
     * @param array $data {"media":'@Path\filename.jpg'}
     *
     * @return boolean|array
     */
    public function uploadImg($data){
        if (!$this->accessToken && !$this->checkAuth()) return false;
        //原先的上传多媒体文件接口使用 self::UPLOAD_MEDIA_URL 前缀
        $result = $this->httpPost(self::API_URL_PREFIX.self::MEDIA_UPLOADIMG_URL.'access_token='.$this->accessToken,$data,true);
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
     * 上传永久素材(认证后的订阅号可用)
     * 新增的永久素材也可以在公众平台官网素材管理模块中看到
     * 注意：上传大文件时可能需要先调用 set_time_limit(0) 避免超时
     * 注意：数组的键值任意，但文件名前必须加@，使用单引号以避免本地路径斜杠被转义
     * @param array $data {"media":'@Path\filename.jpg'}
     * @param string $type 类型：图片:image 语音:voice 视频:video 缩略图:thumb
     * @param boolean $is_video 是否为视频文件，默认为否
     * @param array $video_info 视频信息数组，非视频素材不需要提供 array('title'=>'视频标题','introduction'=>'描述')
     * @return boolean|array
     */
    public function uploadForeverMedia($data, $type,$is_video=false,$video_info=array()){
        if (!$this->accessToken && !$this->checkAuth()) return false;
        //#TODO 暂不确定此接口是否需要让视频文件走http协议
        //如果要获取的素材是视频文件时，不能使用https协议，必须更换成http协议
        //$url_prefix = $is_video?str_replace('https','http',self::API_URL_PREFIX):self::API_URL_PREFIX;
        //当上传视频文件时，附加视频文件信息
        if ($is_video) $data['description'] = self::jsonEncode($video_info);
        $result = $this->httpPost(self::API_URL_PREFIX.self::MEDIA_FOREVER_UPLOAD_URL.'access_token='.$this->accessToken.'&type='.$type,$data,true);
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
     * 上传永久图文素材(认证后的订阅号可用)
     * 新增的永久素材也可以在公众平台官网素材管理模块中看到
     * @param array $data 消息结构{"articles":[{...}]}
     * @return boolean|array
     */
    public function uploadForeverArticles($data){
        if (!$this->accessToken && !$this->checkAuth()) return false;
        $result = $this->httpPost(self::API_URL_PREFIX.self::MEDIA_FOREVER_NEWS_UPLOAD_URL.'access_token='.$this->accessToken,self::jsonEncode($data));
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
     * 修改永久图文素材(认证后的订阅号可用)
     * 永久素材也可以在公众平台官网素材管理模块中看到
     * @param string $media_id 图文素材id
     * @param array $data 消息结构{"articles":[{...}]}
     * @param int $index 更新的文章在图文素材的位置，第一篇为0，仅多图文使用
     * @return boolean|array
     */
    public function updateForeverArticles($media_id,$data,$index=0){
        if (!$this->accessToken && !$this->checkAuth()) return false;
        if (!isset($data['media_id'])) $data['media_id'] = $media_id;
        if (!isset($data['index'])) $data['index'] = $index;
        $result = $this->httpPost(self::API_URL_PREFIX.self::MEDIA_FOREVER_NEWS_UPDATE_URL.'access_token='.$this->accessToken,self::jsonEncode($data));
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
     * 获取永久素材(认证后的订阅号可用)
     * 返回图文消息数组或二进制数据，失败返回false
     * @param string $media_id 媒体文件id
     * @param boolean $is_video 是否为视频文件，默认为否
     * @return boolean|array data
     */
    public function getForeverMedia($media_id,$is_video=false){
        if (!$this->accessToken && !$this->checkAuth()) return false;
        $data = array('media_id' => $media_id);
        //#TODO 暂不确定此接口是否需要让视频文件走http协议
        //如果要获取的素材是视频文件时，不能使用https协议，必须更换成http协议
        //$url_prefix = $is_video?str_replace('https','http',self::API_URL_PREFIX):self::API_URL_PREFIX;
        $result = $this->httpPost(self::API_URL_PREFIX.self::MEDIA_FOREVER_GET_URL.'access_token='.$this->accessToken,self::jsonEncode($data));
        if ($result)
        {
            if (is_string($result)) {
                $json = json_decode($result,true);
                if ($json) {
                    if (isset($json['errcode'])) {
                        $this->errCode = $json['errcode'];
                        $this->errMsg = $json['errmsg'];
                        return false;
                    }
                    return $json;
                } else {
                    return $result;
                }
            }
            return $result;
        }
        return false;
    }

    /**
     * 删除永久素材(认证后的订阅号可用)
     * @param string $media_id 媒体文件id
     * @return boolean
     */
    public function delForeverMedia($media_id){
        if (!$this->accessToken && !$this->checkAuth()) return false;
        $data = array('media_id' => $media_id);
        $result = $this->httpPost(self::API_URL_PREFIX.self::MEDIA_FOREVER_DEL_URL.'access_token='.$this->accessToken,self::jsonEncode($data));
        if ($result)
        {
            $json = json_decode($result,true);
            if (!$json || !empty($json['errcode'])) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                return false;
            }
            return true;
        }
        return false;
    }

    /**
     * 获取永久素材列表(认证后的订阅号可用)
     * @param string $type 素材的类型,图片（image）、视频（video）、语音 （voice）、图文（news）
     * @param int $offset 全部素材的偏移位置，0表示从第一个素材
     * @param int $count 返回素材的数量，取值在1到20之间
     * @return boolean|array
     * 返回数组格式:
     * array(
     *  'total_count'=>0, //该类型的素材的总数
     *  'item_count'=>0,  //本次调用获取的素材的数量
     *  'item'=>array()   //素材列表数组，内容定义请参考官方文档
     * )
     */
    public function getForeverList($type,$offset,$count){
        if (!$this->accessToken && !$this->checkAuth()) return false;
        $data = array(
            'type' => $type,
            'offset' => $offset,
            'count' => $count,
        );
        $result = $this->httpPost(self::API_URL_PREFIX.self::MEDIA_FOREVER_BATCHGET_URL.'access_token='.$this->accessToken,self::jsonEncode($data));
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

    /**
     * 获取永久素材总数(认证后的订阅号可用)
     * @return boolean|array
     * 返回数组格式:
     * array(
     *  'voice_count'=>0, //语音总数量
     *  'video_count'=>0, //视频总数量
     *  'image_count'=>0, //图片总数量
     *  'news_count'=>0   //图文总数量
     * )
     */
    public function getForeverCount(){
        if (!$this->accessToken && !$this->checkAuth()) return false;
        $result = $this->httpGet(self::API_URL_PREFIX.self::MEDIA_FOREVER_COUNT_URL.'access_token='.$this->accessToken);
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

    /**
     * 上传图文消息素材，用于群发(认证后的订阅号可用)
     * @param array $data 消息结构{"articles":[{...}]}
     * @return boolean|array
     */
    public function uploadArticles($data){
        if (!$this->accessToken && !$this->checkAuth()) return false;
        $result = $this->httpPost(self::API_URL_PREFIX.self::MEDIA_UPLOADNEWS_URL.'access_token='.$this->accessToken,self::jsonEncode($data));
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
     * 上传视频素材(认证后的订阅号可用)
     * @param array $data 消息结构
     * {
     *     "media_id"=>"",     //通过上传媒体接口得到的MediaId
     *     "title"=>"TITLE",    //视频标题
     *     "description"=>"Description"        //视频描述
     * }
     * @return boolean|array
     * {
     *     "type":"video",
     *     "media_id":"mediaid",
     *     "created_at":1398848981
     *  }
     */
    public function uploadMpVideo($data){
        if (!$this->accessToken && !$this->checkAuth()) return false;
        $result = $this->httpPost(self::UPLOAD_MEDIA_URL.self::MEDIA_VIDEO_UPLOAD.'access_token='.$this->accessToken,self::jsonEncode($data));
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
     * 高级群发消息, 根据OpenID列表群发图文消息(订阅号不可用)
     * 	注意：视频需要在调用uploadMedia()方法后，再使用 uploadMpVideo() 方法生成，
     *             然后获得的 mediaid 才能用于群发，且消息类型为 mpvideo 类型。
     * @param array $data 消息结构
     * {
     *     "touser"=>array(
     *         "OPENID1",
     *         "OPENID2"
     *     ),
     *      "msgtype"=>"mpvideo",
     *      // 在下面5种类型中选择对应的参数内容
     *      // mpnews | voice | image | mpvideo => array( "media_id"=>"MediaId")
     *      // text => array ( "content" => "hello")
     * }
     * @return boolean|array
     */
    public function sendMassMessage($data){
        if (!$this->accessToken && !$this->checkAuth()) return false;
        $result = $this->httpPost(self::API_URL_PREFIX.self::MASS_SEND_URL.'access_token='.$this->accessToken,self::jsonEncode($data));
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
     * 高级群发消息, 根据群组id群发图文消息(认证后的订阅号可用)
     * 	注意：视频需要在调用uploadMedia()方法后，再使用 uploadMpVideo() 方法生成，
     *             然后获得的 mediaid 才能用于群发，且消息类型为 mpvideo 类型。
     * @param array $data 消息结构
     * {
     *     "filter"=>array(
     *         "is_to_all"=>False,     //是否群发给所有用户.True不用分组id，False需填写分组id
     *         "group_id"=>"2"     //群发的分组id
     *     ),
     *      "msgtype"=>"mpvideo",
     *      // 在下面5种类型中选择对应的参数内容
     *      // mpnews | voice | image | mpvideo => array( "media_id"=>"MediaId")
     *      // text => array ( "content" => "hello")
     * }
     * @return boolean|array
     */
    public function sendGroupMassMessage($data){
        if (!$this->accessToken && !$this->checkAuth()) return false;
        $result = $this->httpPost(self::API_URL_PREFIX.self::MASS_SEND_GROUP_URL.'access_token='.$this->accessToken,self::jsonEncode($data));
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
     * 高级群发消息, 删除群发图文消息(认证后的订阅号可用)
     * @param int $msg_id 消息id
     * @return boolean|array
     */
    public function deleteMassMessage($msg_id){
        if (!$this->accessToken && !$this->checkAuth()) return false;
        $result = $this->httpPost(self::API_URL_PREFIX.self::MASS_DELETE_URL.'access_token='.$this->accessToken,self::jsonEncode(array('msg_id'=>$msg_id)));
        if ($result)
        {
            $json = json_decode($result,true);
            if (!$json || !empty($json['errcode'])) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                return false;
            }
            return true;
        }
        return false;
    }

    /**
     * 高级群发消息, 预览群发消息(认证后的订阅号可用)
     * 	注意：视频需要在调用uploadMedia()方法后，再使用 uploadMpVideo() 方法生成，
     *             然后获得的 mediaid 才能用于群发，且消息类型为 mpvideo 类型。
     * @param array $data 消息结构
     * {
     *     "touser"=>"OPENID",
     *      "msgtype"=>"mpvideo",
     *      // 在下面5种类型中选择对应的参数内容
     *      // mpnews | voice | image | mpvideo => array( "media_id"=>"MediaId")
     *      // text => array ( "content" => "hello")
     * }
     * @return boolean|array
     */
    public function previewMassMessage($data){
        if (!$this->accessToken && !$this->checkAuth()) return false;
        $result = $this->httpPost(self::API_URL_PREFIX.self::MASS_PREVIEW_URL.'access_token='.$this->accessToken,self::jsonEncode($data));
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
     * 高级群发消息, 查询群发消息发送状态(认证后的订阅号可用)
     * @param int $msg_id 消息id
     * @return boolean|array
     * {
     *     "msg_id":201053012,     //群发消息后返回的消息id
     *     "msg_status":"SEND_SUCCESS" //消息发送后的状态，SENDING表示正在发送 SEND_SUCCESS表示发送成功
     * }
     */
    public function queryMassMessage($msg_id){
        if (!$this->accessToken && !$this->checkAuth()) return false;
        $result = $this->httpPost(self::API_URL_PREFIX.self::MASS_QUERY_URL.'access_token='.$this->accessToken,self::jsonEncode(array('msg_id'=>$msg_id)));
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
     * 创建二维码ticket
     * @param int|string $scene_id 自定义追踪id,临时二维码只能用数值型
     * @param int $type 0:临时二维码；1:数值型永久二维码(此时expire参数无效)；2:字符串型永久二维码(此时expire参数无效)
     * @param int $expire 临时二维码有效期，最大为604800秒
     * @return array('ticket'=>'qrcode字串','expire_seconds'=>604800,'url'=>'二维码图片解析后的地址')
     */
    public function getQRCode($scene_id,$type=0,$expire=604800){
        if (!$this->accessToken && !$this->checkAuth()) return false;
        if (!isset($scene_id)) return false;
        switch ($type) {
            case '0':
                if (!is_numeric($scene_id))
                    return false;
                $action_name = 'QR_SCENE';
                $action_info = array('scene'=>(array('scene_id'=>$scene_id)));
                break;

            case '1':
                if (!is_numeric($scene_id))
                    return false;
                $action_name = 'QR_LIMIT_SCENE';
                $action_info = array('scene'=>(array('scene_id'=>$scene_id)));
                break;

            case '2':
                if (!is_string($scene_id))
                    return false;
                $action_name = 'QR_LIMIT_STR_SCENE';
                $action_info = array('scene'=>(array('scene_str'=>$scene_id)));
                break;

            default:
                return false;
        }

        $data = array(
            'action_name'    => $action_name,
            'expire_seconds' => $expire,
            'action_info'    => $action_info
        );
        if ($type) {
            unset($data['expire_seconds']);
        }

        $result = $this->httpPost(self::API_URL_PREFIX.self::QRCODE_CREATE_URL.'access_token='.$this->accessToken,self::jsonEncode($data));
        if ($result) {
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
     * 获取二维码图片
     * @param string $ticket 传入由getQRCode方法生成的ticket参数
     * @return string url 返回http地址
     */
    public function getQRUrl($ticket) {
        return self::QRCODE_IMG_URL.urlencode($ticket);
    }

    /**
     * 长链接转短链接接口
     * @param string $long_url 传入要转换的长url
     * @return boolean|string url 成功则返回转换后的短url
     */
    public function getShortUrl($long_url){
        if (!$this->accessToken && !$this->checkAuth()) return false;
        $data = array(
            'action'=>'long2short',
            'long_url'=>$long_url
        );
        $result = $this->httpPost(self::API_URL_PREFIX.self::SHORT_URL.'access_token='.$this->accessToken,self::jsonEncode($data));
        if ($result)
        {
            $json = json_decode($result,true);
            if (!$json || !empty($json['errcode'])) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                return false;
            }
            return $json['short_url'];
        }
        return false;
    }

    /**
     * 获取统计数据
     * @param string $type  数据分类(user|article|upstreammsg|interface)分别为(用户分析|图文分析|消息分析|接口分析)
     * @param string $subtype   数据子分类，参考 DATACUBE_URL_ARR 常量定义部分 或者README.md说明文档
     * @param string $begin_date 开始时间
     * @param string $end_date   结束时间
     * @return boolean|array 成功返回查询结果数组，其定义请看官方文档
     */
    public function getDatacube($type,$subtype,$begin_date,$end_date=''){
        if (!$this->accessToken && !$this->checkAuth()) return false;
        if (!isset(self::$DATACUBE_URL_ARR[$type]) || !isset(self::$DATACUBE_URL_ARR[$type][$subtype]))
            return false;
        $data = array(
            'begin_date'=>$begin_date,
            'end_date'=>$end_date?$end_date:$begin_date
        );
        $result = $this->httpPost(self::API_BASE_URL_PREFIX.self::$DATACUBE_URL_ARR[$type][$subtype].'access_token='.$this->accessToken,self::jsonEncode($data));
        if ($result)
        {
            $json = json_decode($result,true);
            if (!$json || !empty($json['errcode'])) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                return false;
            }
            return isset($json['list'])?$json['list']:$json;
        }
        return false;
    }

    /**
     * 批量获取关注用户列表
     * @param string $next_openid
     */
    public function getUserList($next_openid = ''){
        if (!$this->accessToken && !$this->checkAuth()) return false;
        $result = $this->httpGet(self::API_URL_PREFIX.self::USER_GET_URL.'access_token='.$this->accessToken.'&next_openid='.$next_openid);
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



    /**
     * 设置用户备注名
     * @param string $openid
     * @param string $remark 备注名
     * @return boolean|array
     */
    public function updateUserRemark($openid,$remark){
        if (!$this->accessToken && !$this->checkAuth()) return false;
        $data = array(
            'openid'=>$openid,
            'remark'=>$remark
        );
        $result = $this->httpPost(self::API_URL_PREFIX.self::USER_UPDATEREMARK_URL.'access_token='.$this->accessToken,self::jsonEncode($data));
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
     * 获取用户分组列表
     * @return boolean|array
     */
    public function getGroup(){
        if (!$this->accessToken && !$this->checkAuth()) return false;
        $result = $this->httpGet(self::API_URL_PREFIX.self::GROUP_GET_URL.'access_token='.$this->accessToken);
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

    /**
     * 获取用户所在分组
     * @param string $openid
     * @return boolean|int 成功则返回用户分组id
     */
    public function getUserGroup($openid){
        if (!$this->accessToken && !$this->checkAuth()) return false;
        $data = array(
            'openid'=>$openid
        );
        $result = $this->httpPost(self::API_URL_PREFIX.self::USER_GROUP_URL.'access_token='.$this->accessToken,self::jsonEncode($data));
        if ($result)
        {
            $json = json_decode($result,true);
            if (!$json || !empty($json['errcode'])) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                return false;
            } else
                if (isset($json['groupid'])) return $json['groupid'];
        }
        return false;
    }

    /**
     * 新增自定分组
     * @param string $name 分组名称
     * @return boolean|array
     */
    public function createGroup($name){
        if (!$this->accessToken && !$this->checkAuth()) return false;
        $data = array(
            'group'=>array('name'=>$name)
        );
        $result = $this->httpPost(self::API_URL_PREFIX.self::GROUP_CREATE_URL.'access_token='.$this->accessToken,self::jsonEncode($data));
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
     * 更改分组名称
     * @param int $groupid 分组id
     * @param string $name 分组名称
     * @return boolean|array
     */
    public function updateGroup($groupid,$name){
        if (!$this->accessToken && !$this->checkAuth()) return false;
        $data = array(
            'group'=>array('id'=>$groupid,'name'=>$name)
        );
        $result = $this->httpPost(self::API_URL_PREFIX.self::GROUP_UPDATE_URL.'access_token='.$this->accessToken,self::jsonEncode($data));
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
     * 移动用户分组
     * @param int $groupid 分组id
     * @param string $openid 用户openid
     * @return boolean|array
     */
    public function updateGroupMembers($groupid,$openid){
        if (!$this->accessToken && !$this->checkAuth()) return false;
        $data = array(
            'openid'=>$openid,
            'to_groupid'=>$groupid
        );
        $result = $this->httpPost(self::API_URL_PREFIX.self::GROUP_MEMBER_UPDATE_URL.'access_token='.$this->accessToken,self::jsonEncode($data));
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
     * 批量移动用户分组
     * @param int $groupid 分组id
     * @param string $openid_list 用户openid数组,一次不能超过50个
     * @return boolean|array
     */
    public function batchUpdateGroupMembers($groupid,$openid_list){
        if (!$this->accessToken && !$this->checkAuth()) return false;
        $data = array(
            'openid_list'=>$openid_list,
            'to_groupid'=>$groupid
        );
        $result = $this->httpPost(self::API_URL_PREFIX.self::GROUP_MEMBER_BATCHUPDATE_URL.'access_token='.$this->accessToken,self::jsonEncode($data));
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
     * 发送客服消息
     * @param array $data 消息结构{"touser":"OPENID","msgtype":"news","news":{...}}
     * @return boolean|array
     */
    public function sendCustomMessage($data){
        if (!$this->accessToken && !$this->checkAuth()) return false;
        $result = $this->httpPost(self::API_URL_PREFIX.self::CUSTOM_SEND_URL.'access_token='.$this->accessToken,self::jsonEncode($data));
        if ($result) {
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
     * 通过code获取Access Token
     * @return array {access_token,expires_in,refresh_token,openid,scope}
     */
    public function getOauthAccessToken(){
        $code = isset($_GET['code'])?$_GET['code']:'';
        if (!$code) return false;
        $result = $this->httpGet(self::API_BASE_URL_PREFIX.self::OAUTH_TOKEN_URL.'appid='.$this->appId.'&secret='.$this->appSecret.'&code='.$code.'&grant_type=authorization_code');
        if ($result)
        {
            $json = json_decode($result,true);
            if (!$json || !empty($json['errcode'])) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                return false;
            }
            $this->user_token = $json['access_token'];
            return $json;
        }
        return false;
    }

    /**
     * 刷新access token并续期
     * @param string $refresh_token
     * @return boolean|mixed
     */
    public function getOauthRefreshToken($refresh_token){
        $result = $this->httpGet(self::API_BASE_URL_PREFIX.self::OAUTH_REFRESH_URL.'appid='.$this->appId.'&grant_type=refresh_token&refresh_token='.$refresh_token);
        if ($result)
        {
            $json = json_decode($result,true);
            if (!$json || !empty($json['errcode'])) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                return false;
            }
            $this->user_token = $json['access_token'];
            return $json;
        }
        return false;
    }

    /**
     * 获取授权后的用户资料
     * @param string $access_token
     * @param string $openid
     * @return array {openid,nickname,sex,province,city,country,headimgurl,privilege,[unionid]}
     * 注意：unionid字段 只有在用户将公众号绑定到微信开放平台账号后，才会出现。建议调用前用isset()检测一下
     */
    public function getOauthUserinfo($access_token,$openid){
        $result = $this->httpGet(self::API_BASE_URL_PREFIX.self::OAUTH_USERINFO_URL.'access_token='.$access_token.'&openid='.$openid);
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
     * 检验授权凭证是否有效
     * @param string $access_token
     * @param string $openid
     * @return boolean 是否有效
     */
    public function getOauthAuth($access_token,$openid){
        $result = $this->httpGet(self::API_BASE_URL_PREFIX.self::OAUTH_AUTH_URL.'access_token='.$access_token.'&openid='.$openid);
        if ($result)
        {
            $json = json_decode($result,true);
            if (!$json || !empty($json['errcode'])) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                return false;
            } else
                if ($json['errcode']==0) return true;
        }
        return false;
    }

    /**
     * 模板消息 设置所属行业
     * @param int $id1 公众号模板消息所属行业编号，参看官方开发文档 行业代码
     * @param int|string $id2 同$id1。但如果只有一个行业，此参数可省略
     * @return array|bool
     */
    public function setTMIndustry($id1, $id2 = ''){
        if ($id1) $data['industry_id1'] = $id1;
        if ($id2) $data['industry_id2'] = $id2;
        if (!$this->accessToken && !$this->checkAuth()) return false;
        $result = $this->httpPost(self::API_URL_PREFIX.self::TEMPLATE_SET_INDUSTRY_URL.'access_token='.$this->accessToken,self::jsonEncode($data));
        if($result){
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
     * 模板消息 添加消息模板
     * 成功返回消息模板的调用id
     * @param string $tpl_id 模板库中模板的编号，有“TM**”和“OPENTMTM**”等形式
     * @return boolean|string
     */
    public function addTemplateMessage($tpl_id){
        $data = array ('template_id_short' =>$tpl_id);
        if (!$this->accessToken && !$this->checkAuth()) return false;
        $result = $this->httpPost(self::API_URL_PREFIX.self::TEMPLATE_ADD_TPL_URL.'access_token='.$this->accessToken,self::jsonEncode($data));
        if($result){
            $json = json_decode($result,true);
            if (!$json || !empty($json['errcode'])) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                return false;
            }
            return $json['template_id'];
        }
        return false;
    }

    /**
     * 发送模板消息
     * @param array $data 消息结构
     * ｛
    "touser":"OPENID",
    "template_id":"ngqIpbwh8bUfcSsECmogfXcV14J0tQlEpBO27izEYtY",
    "url":"http://weixin.qq.com/download",
    "topcolor":"#FF0000",
    "data":{
    "参数名1": {
    "value":"参数",
    "color":"#173177"	 //参数颜色
    },
    "Date":{
    "value":"06月07日 19时24分",
    "color":"#173177"
    },
    "CardNumber":{
    "value":"0426",
    "color":"#173177"
    },
    "Type":{
    "value":"消费",
    "color":"#173177"
    }
    }
    }
     * @return boolean|array
     */
    public function sendTemplateMessage($data){
        if (!$this->accessToken && !$this->checkAuth()) return false;
        $result = $this->httpPost(self::API_URL_PREFIX.self::TEMPLATE_SEND_URL.'access_token='.$this->accessToken,self::jsonEncode($data));
        if($result){
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
     * 获取多客服会话记录
     * @param array $data 数据结构{"starttime":123456789,"endtime":987654321,"openid":"OPENID","pagesize":10,"pageindex":1,}
     * @return boolean|array
     */
    public function getCustomServiceMessage($data){
        if (!$this->accessToken && !$this->checkAuth()) return false;
        $result = $this->httpPost(self::API_URL_PREFIX.self::CUSTOM_SERVICE_GET_RECORD.'access_token='.$this->accessToken,self::jsonEncode($data));
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
     * 转发多客服消息
     * Example: $obj->transfer_customer_service($customer_account)->reply();
     * @param string $customer_account 转发到指定客服帐号：test1@test
     *
     * @return $this
     */
    public function transfer_customer_service($customer_account = '')
    {
        $msg = array(
            'ToUserName' => $this->getRevFrom(),
            'FromUserName'=>$this->getRevTo(),
            'CreateTime'=>time(),
            'MsgType'=>'transfer_customer_service',
        );
        if ($customer_account) {
            $msg['TransInfo'] = array('KfAccount'=>$customer_account);
        }
        $this->Message($msg);
        return $this;
    }

    /**
     * 获取多客服客服基本信息
     *
     * @return boolean|array
     */
    public function getCustomServiceKFlist(){
        if (!$this->accessToken && !$this->checkAuth()) return false;
        $result = $this->httpGet(self::API_URL_PREFIX.self::CUSTOM_SERVICE_GET_KFLIST.'access_token='.$this->accessToken);
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
     * 获取多客服在线客服接待信息
     *
     * @return boolean|array {
    "kf_online_list": [
    {
    "kf_account": "test1@test",	//客服账号@微信别名
    "status": 1,			//客服在线状态 1：pc在线，2：手机在线,若pc和手机同时在线则为 1+2=3
    "kf_id": "1001",		//客服工号
    "auto_accept": 0,		//客服设置的最大自动接入数
    "accepted_case": 1		//客服当前正在接待的会话数
    }
    ]
    }
     */
    public function getCustomServiceOnlineKFlist(){
        if (!$this->accessToken && !$this->checkAuth()) return false;
        $result = $this->httpGet(self::API_URL_PREFIX.self::CUSTOM_SERVICE_GET_ONLINEKFLIST.'access_token='.$this->accessToken);
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
     * 创建指定多客服会话
     * @tutorial 当用户已被其他客服接待或指定客服不在线则会失败
     * @param string $openid           //用户openid
     * @param string $kf_account     //客服账号
     * @param string $text                 //附加信息，文本会展示在客服人员的多客服客户端，可为空
     * @return boolean | array            //成功返回json数组
     * {
     *   "errcode": 0,
     *   "errmsg": "ok",
     * }
     */
    public function createKFSession($openid,$kf_account,$text=''){
        $data=array(
            "openid" =>$openid,
            "kf_account" => $kf_account
        );
        if ($text) $data["text"] = $text;
        if (!$this->accessToken && !$this->checkAuth()) return false;
        $result = $this->httpPost(self::API_BASE_URL_PREFIX.self::CUSTOM_SESSION_CREATE.'access_token='.$this->accessToken,self::jsonEncode($data));
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
     * 关闭指定多客服会话
     * @tutorial 当用户被其他客服接待时则会失败
     * @param string $openid           //用户openid
     * @param string $kf_account     //客服账号
     * @param string $text                 //附加信息，文本会展示在客服人员的多客服客户端，可为空
     * @return boolean | array            //成功返回json数组
     * {
     *   "errcode": 0,
     *   "errmsg": "ok",
     * }
     */
    public function closeKFSession($openid,$kf_account,$text=''){
        $data=array(
            "openid" =>$openid,
            "kf_account" => $kf_account
        );
        if ($text) $data["text"] = $text;
        if (!$this->accessToken && !$this->checkAuth()) return false;
        $result = $this->httpPost(self::API_BASE_URL_PREFIX.self::CUSTOM_SESSION_CLOSE .'access_token='.$this->accessToken,self::jsonEncode($data));
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
     * 获取用户会话状态
     * @param string $openid           //用户openid
     * @return boolean | array            //成功返回json数组
     * {
     *     "errcode" : 0,
     *     "errmsg" : "ok",
     *     "kf_account" : "test1@test",    //正在接待的客服
     *     "createtime": 123456789,        //会话接入时间
     *  }
     */
    public function getKFSession($openid){
        if (!$this->accessToken && !$this->checkAuth()) return false;
        $result = $this->httpGet(self::API_BASE_URL_PREFIX.self::CUSTOM_SESSION_GET .'access_token='.$this->accessToken.'&openid='.$openid);
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
     * 获取指定客服的会话列表
     * @param $kf_account
     * @return array|bool //成功返回json数组
     *  array(
     * 'sessionlist' => array (
     * array (
     * 'openid'=>'OPENID',             //客户 openid
     * 'createtime'=>123456789,  //会话创建时间，UNIX 时间戳
     * ),
     * array (
     * 'openid'=>'OPENID',             //客户 openid
     * 'createtime'=>123456789,  //会话创建时间，UNIX 时间戳
     * ),
     * )
     * )
     * @internal param string $openid //用户openid
     */
    public function getKFSessionlist($kf_account){
        if (!$this->accessToken && !$this->checkAuth()) return false;
        $result = $this->httpGet(self::API_BASE_URL_PREFIX.self::CUSTOM_SESSION_GET_LIST .'access_token='.$this->accessToken.'&kf_account='.$kf_account);
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
     * 获取未接入会话列表
     * @return array|bool //成功返回json数组
     *  array (
     * 'count' => 150 ,                            //未接入会话数量
     * 'waitcaselist' => array (
     * array (
     * 'openid'=>'OPENID',             //客户 openid
     * 'kf_account ' =>'',                   //指定接待的客服，为空则未指定
     * 'createtime'=>123456789,  //会话创建时间，UNIX 时间戳
     * ),
     * array (
     * 'openid'=>'OPENID',             //客户 openid
     * 'kf_account ' =>'',                   //指定接待的客服，为空则未指定
     * 'createtime'=>123456789,  //会话创建时间，UNIX 时间戳
     * )
     * )
     * )
     * @internal param string $openid //用户openid
     */
    public function getKFSessionWait(){
        if (!$this->accessToken && !$this->checkAuth()) return false;
        $result = $this->httpGet(self::API_BASE_URL_PREFIX.self::CUSTOM_SESSION_GET_WAIT .'access_token='.$this->accessToken);
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
     * 添加客服账号
     *
     * @param string $account      //完整客服账号，格式为：账号前缀@公众号微信号，账号前缀最多10个字符，必须是英文或者数字字符
     * @param string $nickname     //客服昵称，最长6个汉字或12个英文字符
     * @param string $password     //客服账号明文登录密码，会自动加密
     * @return boolean|array
     * 成功返回结果
     * {
     *   "errcode": 0,
     *   "errmsg": "ok",
     * }
     */
    public function addKFAccount($account,$nickname,$password){
        $data=array(
            "kf_account" =>$account,
            "nickname" => $nickname,
            "password" => md5($password)
        );
        if (!$this->accessToken && !$this->checkAuth()) return false;
        $result = $this->httpPost(self::API_BASE_URL_PREFIX.self::CS_KF_ACCOUNT_ADD_URL.'access_token='.$this->accessToken,self::jsonEncode($data));
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
     * 修改客服账号信息
     *
     * @param string $account      //完整客服账号，格式为：账号前缀@公众号微信号，账号前缀最多10个字符，必须是英文或者数字字符
     * @param string $nickname     //客服昵称，最长6个汉字或12个英文字符
     * @param string $password     //客服账号明文登录密码，会自动加密
     * @return boolean|array
     * 成功返回结果
     * {
     *   "errcode": 0,
     *   "errmsg": "ok",
     * }
     */
    public function updateKFAccount($account,$nickname,$password){
        $data=array(
            "kf_account" =>$account,
            "nickname" => $nickname,
            "password" => md5($password)
        );
        if (!$this->accessToken && !$this->checkAuth()) return false;
        $result = $this->httpPost(self::API_BASE_URL_PREFIX.self::CS_KF_ACCOUNT_UPDATE_URL.'access_token='.$this->accessToken,self::jsonEncode($data));
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
     * 删除客服账号
     *
     * @param string $account      //完整客服账号，格式为：账号前缀@公众号微信号，账号前缀最多10个字符，必须是英文或者数字字符
     * @return boolean|array
     * 成功返回结果
     * {
     *   "errcode": 0,
     *   "errmsg": "ok",
     * }
     */
    public function deleteKFAccount($account){
        if (!$this->accessToken && !$this->checkAuth()) return false;
        $result = $this->httpGet(self::API_BASE_URL_PREFIX.self::CS_KF_ACCOUNT_DEL_URL.'access_token='.$this->accessToken.'&kf_account='.$account);
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
     * 上传客服头像
     *
     * @param string $account //完整客服账号，格式为：账号前缀@公众号微信号，账号前缀最多10个字符，必须是英文或者数字字符
     * @param string $imgfile //头像文件完整路径,如：'D:\user.jpg'。头像文件必须JPG格式，像素建议640*640
     * @return boolean|array
     * 成功返回结果
     * {
     *   "errcode": 0,
     *   "errmsg": "ok",
     * }
     */
    public function setKFHeadImg($account,$imgfile){
        if (!$this->accessToken && !$this->checkAuth()) return false;
        $result = $this->httpPost(self::API_BASE_URL_PREFIX.self::CS_KF_ACCOUNT_UPLOAD_HEADIMG_URL.'access_token='.$this->accessToken.'&kf_account='.$account,array('media'=>'@'.$imgfile),true);
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
     * 语义理解接口
     * @param String $uid 用户唯一id（非开发者id），用户区分公众号下的不同用户（建议填入用户openid）
     * @param String $query 输入文本串
     * @param String $category 需要使用的服务类型，多个用“，”隔开，不能为空
     * @param Float|int $latitude 纬度坐标，与经度同时传入；与城市二选一传入
     * @param Float|int $longitude 经度坐标，与纬度同时传入；与城市二选一传入
     * @param String $city 城市名称，与经纬度二选一传入
     * @param String $region 区域名称，在城市存在的情况下可省略；与经纬度二选一传入
     * @return array|bool
     */
    public function querySemantic($uid, $query, $category, $latitude = 0, $longitude = 0, $city = "", $region = ""){
        if (!$this->accessToken && !$this->checkAuth()) return false;
        $data=array(
            'query' => $query,
            'category' => $category,
            'appid' => $this->appId,
            'uid' => ''
        );
        //地理坐标或城市名称二选一
        if ($latitude) {
            $data['latitude'] = $latitude;
            $data['longitude'] = $longitude;
        } elseif ($city) {
            $data['city'] = $city;
        } elseif ($region) {
            $data['region'] = $region;
        }
        $result = $this->httpPost(self::API_BASE_URL_PREFIX.self::SEMANTIC_API_URL.'access_token='.$this->accessToken,self::jsonEncode($data));
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
     * 创建卡券
     * @param array $data      卡券数据
     * @return array|boolean 返回数组中card_id为卡券ID
     */
    public function createCard($data) {
        if (!$this->accessToken && !$this->checkAuth()) return false;
        $result = $this->httpPost(self::API_BASE_URL_PREFIX . self::CARD_CREATE . 'access_token=' . $this->accessToken, self::jsonEncode($data));
        if ($result) {
            $json = json_decode($result, true);
            if (!$json || !empty($json['errcode'])) {
                $this->errCode = $json['errcode'];
                $this->errMsg  = $json['errmsg'];
                return false;
            }
            return $json;
        }
        return false;
    }

    /**
     * 更改卡券信息
     * 调用该接口更新信息后会重新送审，卡券状态变更为待审核。已被用户领取的卡券会实时更新票面信息。
     * @param string $data
     * @return boolean
     */
    public function updateCard($data) {
        if (!$this->accessToken && !$this->checkAuth()) return false;
        $result = $this->httpPost(self::API_BASE_URL_PREFIX . self::CARD_UPDATE . 'access_token=' . $this->accessToken, self::jsonEncode($data));
        if ($result) {
            $json = json_decode($result, true);
            if (!$json || !empty($json['errcode'])) {
                $this->errCode = $json['errcode'];
                $this->errMsg  = $json['errmsg'];
                return false;
            }
            return true;
        }
        return false;
    }

    /**
     * 删除卡券
     * 允许商户删除任意一类卡券。删除卡券后，该卡券对应已生成的领取用二维码、添加到卡包 JS API 均会失效。
     * 注意：删除卡券不能删除已被用户领取，保存在微信客户端中的卡券，已领取的卡券依旧有效。
     * @param string $card_id 卡券ID
     * @return boolean
     */
    public function delCard($card_id) {
        $data = array(
            'card_id' => $card_id,
        );
        if (!$this->accessToken && !$this->checkAuth()) return false;
        $result = $this->httpPost(self::API_BASE_URL_PREFIX . self::CARD_DELETE . 'access_token=' . $this->accessToken, self::jsonEncode($data));
        if ($result) {
            $json = json_decode($result, true);
            if (!$json || !empty($json['errcode'])) {
                $this->errCode = $json['errcode'];
                $this->errMsg  = $json['errmsg'];
                return false;
            }
            return true;
        }
        return false;
    }

    /**
     * 查询卡券详情
     * @param string $card_id
     * @return boolean|array    返回数组信息比较复杂，请参看卡券接口文档
     */
    public function getCardInfo($card_id) {
        $data = array(
            'card_id' => $card_id,
        );
        if (!$this->accessToken && !$this->checkAuth()) return false;
        $result = $this->httpPost(self::API_BASE_URL_PREFIX . self::CARD_GET . 'access_token=' . $this->accessToken, self::jsonEncode($data));
        if ($result) {
            $json = json_decode($result, true);
            if (!$json || !empty($json['errcode'])) {
                $this->errCode = $json['errcode'];
                $this->errMsg  = $json['errmsg'];
                return false;
            }
            return $json;
        }
        return false;
    }

    /**
     * 获取颜色列表
     * 获得卡券的最新颜色列表，用于创建卡券
     * @return boolean|array   返回数组请参看 微信卡券接口文档 的json格式
     */
    public function getCardColors() {
        if (!$this->accessToken && !$this->checkAuth()) return false;
        $result = $this->httpGet(self::API_BASE_URL_PREFIX . self::CARD_GETCOLORS . 'access_token=' . $this->accessToken);
        if ($result) {
            $json = json_decode($result, true);
            if (!$json || !empty($json['errcode'])) {
                $this->errCode = $json['errcode'];
                $this->errMsg  = $json['errmsg'];
                return false;
            }
            return $json;
        }
        return false;
    }

    /**
     * 拉取门店列表
     * 获取在公众平台上申请创建的门店列表
     * @param int $offset  开始拉取的偏移，默认为0从头开始
     * @param int $count   拉取的数量，默认为0拉取全部
     * @return boolean|array   返回数组请参看 微信卡券接口文档 的json格式
     */
    public function getCardLocations($offset=0,$count=0) {
        $data=array(
            'offset'=>$offset,
            'count'=>$count
        );
        if (!$this->accessToken && !$this->checkAuth()) return false;
        $result = $this->httpPost(self::API_BASE_URL_PREFIX . self::CARD_LOCATION_BATCHGET . 'access_token=' . $this->accessToken, self::jsonEncode($data));
        if ($result) {
            $json = json_decode($result, true);
            if (!$json || !empty($json['errcode'])) {
                $this->errCode = $json['errcode'];
                $this->errMsg  = $json['errmsg'];
                return false;
            }
            return $json;
        }
        return false;
    }

    /**
     * 批量导入门店信息
     * @tutorial 返回插入的门店id列表，以逗号分隔。如果有插入失败的，则为-1，请自行核查是哪个插入失败
     * @param array $data    数组形式的json数据，由于内容较多，具体内容格式请查看 微信卡券接口文档
     * @return boolean|string 成功返回插入的门店id列表
     */
    public function addCardLocations($data) {
        if (!$this->accessToken && !$this->checkAuth()) return false;
        $result = $this->httpPost(self::API_BASE_URL_PREFIX . self::CARD_LOCATION_BATCHADD . 'access_token=' . $this->accessToken, self::jsonEncode($data));
        if ($result) {
            $json = json_decode($result, true);
            if (!$json || !empty($json['errcode'])) {
                $this->errCode = $json['errcode'];
                $this->errMsg  = $json['errmsg'];
                return false;
            }
            return $json;
        }
        return false;
    }

    /**
     * 生成卡券二维码
     * 成功则直接返回ticket值，可以用 getQRUrl($ticket) 换取二维码url
     *
     * @param $card_id 卡券ID 必须
     * @param string $code 指定卡券 code 码，只能被领一次。use_custom_code 字段为 true 的卡券必须填写，非自定义 code 不必填写。
     * @param string $openid 指定领取者的 openid，只有该用户能领取。bind_openid 字段为 true 的卡券必须填写，非自定义 openid 不必填写。
     * @param int $expire_seconds 指定二维码的有效时间，范围是 60 ~ 1800 秒。不填默认为永久有效。
     * @param boolean $is_unique_code 指定下发二维码，生成的二维码随机分配一个 code，领取后不可再次扫描。填写 true 或 false。默认 false。
     * @param string $balance 红包余额，以分为单位。红包类型必填（LUCKY_MONEY），其他卡券类型不填。
     * @return bool|string
     */
    public function createCardQrcode($card_id,$code='',$openid='',$expire_seconds=0,$is_unique_code=false,$balance='') {
        $card = array(
            'card_id' => $card_id
        );
        $data = array(
            'action_name' => "QR_CARD"
        );
        if ($code)
            $card['code'] = $code;
        if ($openid)
            $card['openid'] = $openid;
        if ($is_unique_code)
            $card['is_unique_code'] = $is_unique_code;
        if ($balance)
            $card['balance'] = $balance;
        if ($expire_seconds)
            $data['expire_seconds'] = $expire_seconds;
        $data['action_info'] = array('card' => $card);
        if (!$this->accessToken && !$this->checkAuth()) return false;
        $result = $this->httpPost(self::API_BASE_URL_PREFIX . self::CARD_QRCODE_CREATE . 'access_token=' . $this->accessToken, self::jsonEncode($data));
        if ($result) {
            $json = json_decode($result, true);
            if (!$json || !empty($json['errcode'])) {
                $this->errCode = $json['errcode'];
                $this->errMsg  = $json['errmsg'];
                return false;
            }
            return $json;
        }
        return false;
    }

    /**
     * 消耗 code
     * 自定义 code（use_custom_code 为 true）的优惠券，在 code 被核销时，必须调用此接口。
     *
     * @param string $code 要消耗的序列号
     * @param string $card_id 要消耗序列号所述的 card_id，创建卡券时use_custom_code 填写 true 时必填。
     * @return boolean|array
     * {
     *  "errcode":0,
     *  "errmsg":"ok",
     *  "card":{"card_id":"pFS7Fjg8kV1IdDz01r4SQwMkuCKc"},
     *  "openid":"oFS7Fjl0WsZ9AMZqrI80nbIq8xrA"
     * }
     */
    public function consumeCardCode($code,$card_id='') {
        $data = array('code' => $code);
        if ($card_id)
            $data['card_id'] = $card_id;
        if (!$this->accessToken && !$this->checkAuth()) return false;
        $result = $this->httpPost(self::API_BASE_URL_PREFIX . self::CARD_CODE_CONSUME . 'access_token=' . $this->accessToken, self::jsonEncode($data));
        if ($result) {
            $json = json_decode($result, true);
            if (!$json || !empty($json['errcode'])) {
                $this->errCode = $json['errcode'];
                $this->errMsg  = $json['errmsg'];
                return false;
            }
            return $json;
        }
        return false;
    }

    /**
     * code 解码
     * @param string $encrypt_code 通过 choose_card_info 获取的加密字符串
     * @return boolean|array
     * {
     *  "errcode":0,
     *  "errmsg":"ok",
     *  "code":"751234212312"
     *  }
     */
    public function decryptCardCode($encrypt_code) {
        $data = array(
            'encrypt_code' => $encrypt_code,
        );
        if (!$this->accessToken && !$this->checkAuth()) return false;
        $result = $this->httpPost(self::API_BASE_URL_PREFIX . self::CARD_CODE_DECRYPT . 'access_token=' . $this->accessToken, self::jsonEncode($data));
        if ($result) {
            $json = json_decode($result, true);
            if (!$json || !empty($json['errcode'])) {
                $this->errCode = $json['errcode'];
                $this->errMsg  = $json['errmsg'];
                return false;
            }
            return $json;
        }
        return false;
    }

    /**
     * 查询 code 的有效性（非自定义 code）
     * @param string $code
     * @return boolean|array
     * {
     *  "errcode":0,
     *  "errmsg":"ok",
     *  "openid":"oFS7Fjl0WsZ9AMZqrI80nbIq8xrA",    //用户 openid
     *  "card":{
     *      "card_id":"pFS7Fjg8kV1IdDz01r4SQwMkuCKc",
     *      "begin_time": 1404205036,               //起始使用时间
     *      "end_time": 1404205036,                 //结束时间
     *  }
     * }
     */
    public function checkCardCode($code) {
        $data = array(
            'code' => $code,
        );
        if (!$this->accessToken && !$this->checkAuth()) return false;
        $result = $this->httpPost(self::API_BASE_URL_PREFIX . self::CARD_CODE_GET . 'access_token=' . $this->accessToken, self::jsonEncode($data));
        if ($result) {
            $json = json_decode($result, true);
            if (!$json || !empty($json['errcode'])) {
                $this->errCode = $json['errcode'];
                $this->errMsg  = $json['errmsg'];
                return false;
            }
            return $json;
        }
        return false;
    }

    /**
     * 批量查询卡列表
     * @param integer $offset  开始拉取的偏移，默认为0从头开始
     * @param integer $count   需要查询的卡片的数量（数量最大50,默认50）
     * @return boolean|array
     * {
     *  "errcode":0,
     *  "errmsg":"ok",
     *  "card_id_list":["ph_gmt7cUVrlRk8swPwx7aDyF-pg"],    //卡 id 列表
     *  "total_num":1                                       //该商户名下 card_id 总数
     * }
     */
    public function getCardIdList($offset=0,$count=50) {
        if ($count>50)
            $count = 50;
        $data = array(
            'offset' => $offset,
            'count'  => $count,
        );
        if (!$this->accessToken && !$this->checkAuth()) return false;
        $result = $this->httpPost(self::API_BASE_URL_PREFIX . self::CARD_BATCHGET . 'access_token=' . $this->accessToken, self::jsonEncode($data));
        if ($result) {
            $json = json_decode($result, true);
            if (!$json || !empty($json['errcode'])) {
                $this->errCode = $json['errcode'];
                $this->errMsg  = $json['errmsg'];
                return false;
            }
            return $json;
        }
        return false;
    }

    /**
     * 更改 code
     * 为确保转赠后的安全性，微信允许自定义code的商户对已下发的code进行更改。
     * 注：为避免用户疑惑，建议仅在发生转赠行为后（发生转赠后，微信会通过事件推送的方式告知商户被转赠的卡券code）对用户的code进行更改。
     * @param string $code      卡券的 code 编码
     * @param string $card_id   卡券 ID
     * @param string $new_code  新的卡券 code 编码
     * @return boolean
     */
    public function updateCardCode($code,$card_id,$new_code) {
        $data = array(
            'code' => $code,
            'card_id' => $card_id,
            'new_code' => $new_code,
        );
        if (!$this->accessToken && !$this->checkAuth()) return false;
        $result = $this->httpPost(self::API_BASE_URL_PREFIX . self::CARD_CODE_UPDATE . 'access_token=' . $this->accessToken, self::jsonEncode($data));
        if ($result) {
            $json = json_decode($result, true);
            if (!$json || !empty($json['errcode'])) {
                $this->errCode = $json['errcode'];
                $this->errMsg  = $json['errmsg'];
                return false;
            }
            return true;
        }
        return false;
    }

    /**
     * 设置卡券失效
     * 设置卡券失效的操作不可逆
     * @param string $code 需要设置为失效的 code
     * @param string $card_id 自定义 code 的卡券必填。非自定义 code 的卡券不填。
     * @return boolean
     */
    public function unavailableCardCode($code,$card_id='') {
        $data = array(
            'code' => $code,
        );
        if ($card_id)
            $data['card_id'] = $card_id;
        if (!$this->accessToken && !$this->checkAuth()) return false;
        $result = $this->httpPost(self::API_BASE_URL_PREFIX . self::CARD_CODE_UNAVAILABLE . 'access_token=' . $this->accessToken, self::jsonEncode($data));
        if ($result) {
            $json = json_decode($result, true);
            if (!$json || !empty($json['errcode'])) {
                $this->errCode = $json['errcode'];
                $this->errMsg  = $json['errmsg'];
                return false;
            }
            return true;
        }
        return false;
    }

    /**
     * 库存修改
     * @param string $data
     * @return boolean
     */
    public function modifyCardStock($data) {
        if (!$this->accessToken && !$this->checkAuth()) return false;
        $result = $this->httpPost(self::API_BASE_URL_PREFIX . self::CARD_MODIFY_STOCK . 'access_token=' . $this->accessToken, self::jsonEncode($data));
        if ($result) {
            $json = json_decode($result, true);
            if (!$json || !empty($json['errcode'])) {
                $this->errCode = $json['errcode'];
                $this->errMsg  = $json['errmsg'];
                return false;
            }
            return true;
        }
        return false;
    }

    /**
     * 更新门票
     * @param string $data
     * @return boolean
     */
    public function updateMeetingCard($data) {
        if (!$this->accessToken && !$this->checkAuth()) return false;
        $result = $this->httpPost(self::API_BASE_URL_PREFIX . self::CARD_MEETINGCARD_UPDATEUSER . 'access_token=' . $this->accessToken, self::jsonEncode($data));
        if ($result) {
            $json = json_decode($result, true);
            if (!$json || !empty($json['errcode'])) {
                $this->errCode = $json['errcode'];
                $this->errMsg  = $json['errmsg'];
                return false;
            }
            return true;
        }
        return false;
    }

    /**
     * 激活/绑定会员卡
     * @param string $data 具体结构请参看卡券开发文档(6.1.1 激活/绑定会员卡)章节
     * @return boolean
     */
    public function activateMemberCard($data) {
        if (!$this->accessToken && !$this->checkAuth()) return false;
        $result = $this->httpPost(self::API_BASE_URL_PREFIX . self::CARD_MEMBERCARD_ACTIVATE . 'access_token=' . $this->accessToken, self::jsonEncode($data));
        if ($result) {
            $json = json_decode($result, true);
            if (!$json || !empty($json['errcode'])) {
                $this->errCode = $json['errcode'];
                $this->errMsg  = $json['errmsg'];
                return false;
            }
            return true;
        }
        return false;
    }

    /**
     * 会员卡交易
     * 会员卡交易后每次积分及余额变更需通过接口通知微信，便于后续消息通知及其他扩展功能。
     * @param string $data 具体结构请参看卡券开发文档(6.1.2 会员卡交易)章节
     * @return boolean|array
     */
    public function updateMemberCard($data) {
        if (!$this->accessToken && !$this->checkAuth()) return false;
        $result = $this->httpPost(self::API_BASE_URL_PREFIX . self::CARD_MEMBERCARD_UPDATEUSER . 'access_token=' . $this->accessToken, self::jsonEncode($data));
        if ($result) {
            $json = json_decode($result, true);
            if (!$json || !empty($json['errcode'])) {
                $this->errCode = $json['errcode'];
                $this->errMsg  = $json['errmsg'];
                return false;
            }
            return $json;
        }
        return false;
    }

    /**
     * 更新红包金额
     * @param string $code      红包的序列号
     * @param integer $balance          红包余额
     * @param string $card_id   自定义 code 的卡券必填。非自定义 code 可不填。
     * @return boolean|array
     */
    public function updateLuckyMoney($code,$balance,$card_id='') {
        $data = array(
            'code' => $code,
            'balance' => $balance
        );
        if ($card_id)
            $data['card_id'] = $card_id;
        if (!$this->accessToken && !$this->checkAuth()) return false;
        $result = $this->httpPost(self::API_BASE_URL_PREFIX . self::CARD_LUCKYMONEY_UPDATE . 'access_token=' . $this->accessToken, self::jsonEncode($data));
        if ($result) {
            $json = json_decode($result, true);
            if (!$json || !empty($json['errcode'])) {
                $this->errCode = $json['errcode'];
                $this->errMsg  = $json['errmsg'];
                return false;
            }
            return true;
        }
        return false;
    }

    /**
     * 设置卡券测试白名单
     * @param array|string $openid 测试的 openid 列表
     * @param array|string $user 测试的微信号列表
     * @return bool
     */
    public function setCardTestWhiteList($openid=array(),$user=array()) {
        $data = array();
        if (count($openid) > 0)
            $data['openid'] = $openid;
        if (count($user) > 0)
            $data['username'] = $user;
        if (!$this->accessToken && !$this->checkAuth()) return false;
        $result = $this->httpPost(self::API_BASE_URL_PREFIX . self::CARD_TESTWHILELIST_SET . 'access_token=' . $this->accessToken, self::jsonEncode($data));
        if ($result) {
            $json = json_decode($result, true);
            if (!$json || !empty($json['errcode'])) {
                $this->errCode = $json['errcode'];
                $this->errMsg  = $json['errmsg'];
                return false;
            }
            return true;
        }
        return false;
    }

    /**
     * 申请设备ID
     * [applyShakeAroundDevice 申请配置设备所需的UUID、Major、Minor。
     * 若激活率小于50%，不能新增设备。单次新增设备超过500 个，需走人工审核流程。
     * 审核通过后，可用迒回的批次ID 用“查询设备列表”接口拉取本次申请的设备ID]
     * @param array $data
     * array(
     *      "quantity" => 3,         //申请的设备ID 的数量，单次新增设备超过500 个,需走人工审核流程(必填)
     *      "apply_reason" => "测试",//申请理由(必填)
     *      "comment" => "测试专用", //备注(非必填)
     *      "poi_id" => 1234         //设备关联的门店ID(非必填)
     * )
     * @return boolean|mixed
     * {
    "data": {
    "apply_id": 123,
    "device_identifiers":[
    {
    "device_id":10100,
    "uuid":"FDA50693-A4E2-4FB1-AFCF-C6EB07647825",
    "major":10001,
    "minor":10002
    }
    ]
    },
    "errcode": 0,
    "errmsg": "success."
    }

    apply_id:申请的批次ID，可用在“查询设备列表”接口按批次查询本次申请成功的设备ID
    device_identifiers:指定的设备ID 列表
    device_id:设备编号
    uuid、major、minor
    audit_status:审核状态。0：审核未通过、1：审核中、2：审核已通过；审核会在三个工作日内完成
    audit_comment:审核备注，包括审核不通过的原因
     * @access public
     * @author polo<gao.bo168@gmail.com>
     * @version 2015-3-25 下午1:24:06
     * @copyright Show More
     */
    public function applyShakeAroundDevice($data){
        if (!$this->accessToken && !$this->checkAuth()) return false;
        $result = $this->httpPost(self::API_BASE_URL_PREFIX . self::SHAKEAROUND_DEVICE_APPLYID . 'access_token=' . $this->accessToken, self::jsonEncode($data));
        $this->log($result);
        if ($result) {
            $json = json_decode($result, true);
            if (!$json || !empty($json['errcode'])) {
                $this->errCode = $json['errcode'];
                $this->errMsg  = $json['errmsg'];
                return false;
            }
            return $json;
        }
        return false;
    }

    /**
     * 编辑设备信息
     * [updateShakeAroundDevice 编辑设备的备注信息。可用设备ID或完整的UUID、Major、Minor指定设备，二者选其一。]
     * @param array $data
     * array(
     *      "device_identifier" => array(
     *          		"device_id" => 10011,   //当提供了device_id则不需要使用uuid、major、minor，反之亦然
     *          		"uuid" => "FDA50693-A4E2-4FB1-AFCF-C6EB07647825",
     *          		"major" => 1002,
     *          		"minor" => 1223
     *      ),
     *      "comment" => "测试专用", //备注(非必填)
     * )
     * {
    "data": {
    },
    "errcode": 0,
    "errmsg": "success."
    }
     * @return boolean
     * @author binsee<binsee@163.com>
     * @version 2015-4-20 23:45:00
     */
    public function updateShakeAroundDevice($data){
        if (!$this->accessToken && !$this->checkAuth()) return false;
        $result = $this->httpPost(self::API_BASE_URL_PREFIX . self::SHAKEAROUND_DEVICE_UPDATE . 'access_token=' . $this->accessToken, self::jsonEncode($data));
        $this->log($result);
        if ($result) {
            $json = json_decode($result, true);
            if (!$json || !empty($json['errcode'])) {
                $this->errCode = $json['errcode'];
                $this->errMsg  = $json['errmsg'];
                return false;
            }
            return true;
        }
        return false;
    }

    /**
     * 查询设备列表
     * [searchShakeAroundDevice 查询已有的设备ID、UUID、Major、Minor、激活状态、备注信息、关联门店、关联页面等信息。
     * 可指定设备ID 或完整的UUID、Major、Minor 查询，也可批量拉取设备信息列表。]
     * @param array $data
     * $data 三种格式:
     * ①查询指定设备时：$data = array(
     *                              "device_identifiers" => array(
     *                                                          array(
     *                                                              "device_id" => 10100,
     *                                                              "uuid" => "FDA50693-A4E2-4FB1-AFCF-C6EB07647825",
     *                                                              "major" => 10001,
     *                                                              "minor" => 10002
     *                                                          )
     *                                                      )
     *                              );
     * device_identifiers:指定的设备
     * device_id:设备编号，若填了UUID、major、minor，则可不填设备编号，若二者都填，则以设备编号为优先
     * uuid、major、minor:三个信息需填写完整，若填了设备编号，则可不填此信息
     * +-------------------------------------------------------------------------------------------------------------
     * ②需要分页查询或者指定范围内的设备时: $data = array(
     *                                                  "begin" => 0,
     *                                                  "count" => 3
     *                                               );
     * begin:设备列表的起始索引值
     * count:待查询的设备个数
     * +-------------------------------------------------------------------------------------------------------------
     * ③当需要根据批次ID 查询时: $data = array(
     *                                      "apply_id" => 1231,
     *                                      "begin" => 0,
     *                                      "count" => 3
     *                                    );
     * apply_id:批次ID
     * +-------------------------------------------------------------------------------------------------------------
     * @return boolean|mixed
     *正确迒回JSON 数据示例：
     *字段说明
    {
    "data": {
    "devices": [          //指定的设备信息列表
    {
    "comment": "", //设备的备注信息
    "device_id": 10097, //设备编号
    "major": 10001,
    "minor": 12102,
    "page_ids": "15369", //与此设备关联的页面ID 列表，用逗号隔开
    "status": 1, //激活状态，0：未激活，1：已激活（但不活跃），2：活跃
    "poi_id": 0, //门店ID
    "uuid": "FDA50693-A4E2-4FB1-AFCF-C6EB07647825"
    },
    {
    "comment": "", //设备的备注信息
    "device_id": 10098, //设备编号
    "major": 10001,
    "minor": 12103,
    "page_ids": "15368", //与此设备关联的页面ID 列表，用逗号隔开
    "status": 1, //激活状态，0：未激活，1：已激活（但不活跃），2：活跃
    "poi_id": 0, //门店ID
    "uuid": "FDA50693-A4E2-4FB1-AFCF-C6EB07647825"
    }
    ],
    "total_count": 151 //商户名下的设备总量
    },
    "errcode": 0,
    "errmsg": "success."
    }
     * @access public
     * @author polo<gao.bo168@gmail.com>
     * @version 2015-3-25 下午1:45:42
     * @copyright Show More
     */
    public function searchShakeAroundDevice($data){
        if (!$this->accessToken && !$this->checkAuth()) return false;
        $result = $this->httpPost(self::API_BASE_URL_PREFIX . self::SHAKEAROUND_DEVICE_SEARCH . 'access_token=' . $this->accessToken, self::jsonEncode($data));
        $this->log($result);
        if ($result) {
            $json = json_decode($result, true);
            if (!$json || !empty($json['errcode'])) {
                $this->errCode = $json['errcode'];
                $this->errMsg  = $json['errmsg'];
                return false;
            }
            return $json;
        }
        return false;
    }

    /**
     * [bindLocationShakeAroundDevice 配置设备与门店的关联关系]
     * @param string $device_id 设备编号，若填了UUID、major、minor，则可不填设备编号，若二者都填，则以设备编号为优先
     * @param int $poi_id 待关联的门店ID
     * @param string $uuid UUID、major、minor，三个信息需填写完整，若填了设备编号，则可不填此信息
     * @param int $major
     * @param int $minor
     * @return boolean|mixed
     * 正确返回JSON 数据示例:
     * {
    "data": {
    },
    "errcode": 0,
    "errmsg": "success."
    }
     * @access public
     * @author polo<gao.bo168@gmail.com>
     * @version 2015-4-21 00:14:00
     * @copyright Show More
     */
    public function bindLocationShakeAroundDevice($device_id,$poi_id,$uuid='',$major=0,$minor=0){
        if (!$this->accessToken && !$this->checkAuth()) return false;
        if(!$device_id){
            if(!$uuid || !$major || !$minor){
                return false;
            }
            $device_identifier = array(
                'uuid' => $uuid,
                'major' => $major,
                'minor' => $minor
            );
        }else{
            $device_identifier = array(
                'device_id' => $device_id
            );
        }
        $data = array(
            'device_identifier' => $device_identifier,
            'poi_id' => $poi_id
        );
        $result = $this->httpPost(self::API_BASE_URL_PREFIX . self::SHAKEAROUND_DEVICE_BINDLOCATION . 'access_token=' . $this->accessToken, self::jsonEncode($data));
        $this->log($result);
        if ($result) {
            $json = json_decode($result, true);
            if (!$json || !empty($json['errcode'])) {
                $this->errCode = $json['errcode'];
                $this->errMsg  = $json['errmsg'];
                return false;
            }
            return $json; //这个可以更改为返回true
        }
        return false;
    }

    /**
     * [bindPageShakeAroundDevice 配置设备与页面的关联关系。
     * 支持建立或解除关联关系，也支持新增页面或覆盖页面等操作。
     * 配置完成后，在此设备的信号范围内，即可摇出关联的页面信息。
     * 若设备配置多个页面，则随机出现页面信息]
     * @param string $device_id 设备编号，若填了UUID、major、minor，则可不填设备编号，若二者都填，则以设备编号为优先
     * @param array $page_ids 待关联的页面列表
     * @param number $bind 关联操作标志位， 0 为解除关联关系，1 为建立关联关系
     * @param number $append 新增操作标志位， 0 为覆盖，1 为新增
     * @param string $uuid UUID、major、minor，三个信息需填写完整，若填了设备编号，则可不填此信息
     * @param int $major
     * @param int $minor
     * @return boolean|mixed
     * 正确返回JSON 数据示例:
     * {
    "data": {
    },
    "errcode": 0,
    "errmsg": "success."
    }
     * @access public
     * @author polo<gao.bo168@gmail.com>
     * @version 2015-4-21 00:31:00
     * @copyright Show More
     */
    public function bindPageShakeAroundDevice($device_id,$page_ids=array(),$bind=1,$append=1,$uuid='',$major=0,$minor=0){
        if (!$this->accessToken && !$this->checkAuth()) return false;
        if(!$device_id){
            if(!$uuid || !$major || !$minor){
                return false;
            }
            $device_identifier = array(
                'uuid' => $uuid,
                'major' => $major,
                'minor' => $minor
            );
        }else{
            $device_identifier = array(
                'device_id' => $device_id
            );
        }
        $data = array(
            'device_identifier' => $device_identifier,
            'page_ids' => $page_ids,
            'bind' => $bind,
            'append' => $append
        );
        $result = $this->httpPost(self::API_BASE_URL_PREFIX . self::SHAKEAROUND_DEVICE_BINDPAGE . 'access_token=' . $this->accessToken, self::jsonEncode($data));
        $this->log($result);
        if ($result) {
            $json = json_decode($result, true);
            if (!$json || !empty($json['errcode'])) {
                $this->errCode = $json['errcode'];
                $this->errMsg  = $json['errmsg'];
                return false;
            }
            return $json;
        }
        return false;
    }

    /**
     * 上传在摇一摇页面展示的图片素材
     * 注意：数组的键值任意，但文件名前必须加@，使用单引号以避免本地路径斜杠被转义
     * @param array $data {"media":'@Path\filename.jpg'} 格式限定为：jpg,jpeg,png,gif，图片大小建议120px*120 px，限制不超过200 px *200 px，图片需为正方形。
     * @return boolean|array
     * {
    "data": {
    "pic_url":"http://shp.qpic.cn/wechat_shakearound_pic/0/1428377032e9dd2797018cad79186e03e8c5aec8dc/120"
    },
    "errcode": 0,
    "errmsg": "success."
    }
    }
     * @author binsee<binsee@163.com>
     * @version 2015-4-21 00:51:00
     */
    public function uploadShakeAroundMedia($data){
        if (!$this->accessToken && !$this->checkAuth()) return false;
        $result = $this->httpPost(self::API_URL_PREFIX.self::SHAKEAROUND_MATERIAL_ADD.'access_token='.$this->accessToken,$data,true);
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
     * [addShakeAroundPage 增加摇一摇出来的页面信息，包括在摇一摇页面出现的主标题、副标题、图片和点击进去的超链接。]
     * @param string $title 在摇一摇页面展示的主标题，不超过6 个字
     * @param string $description 在摇一摇页面展示的副标题，不超过7 个字
     * @param string $icon_url 在摇一摇页面展示的图片， 格式限定为：jpg,jpeg,png,gif; 建议120*120 ， 限制不超过200*200
     * @param string $page_url 跳转链接
     * @param string $comment 页面的备注信息，不超过15 个字,可不填
     * @return boolean|mixed
     * 正确返回JSON 数据示例:
     * {
    "data": {
    "page_id": 28840 //新增页面的页面id
    }
    "errcode": 0,
    "errmsg": "success."
    }
     * @access public
     * @author polo<gao.bo168@gmail.com>
     * @version 2015-3-25 下午2:57:09
     * @copyright Show More
     */
    public function addShakeAroundPage($title,$description,$icon_url,$page_url,$comment=''){
        if (!$this->accessToken && !$this->checkAuth()) return false;
        $data = array(
            "title" => $title,
            "description" => $description,
            "icon_url" => $icon_url,
            "page_url" => $page_url,
            "comment" => $comment
        );
        $result = $this->httpPost(self::API_BASE_URL_PREFIX . self::SHAKEAROUND_PAGE_ADD . 'access_token=' . $this->accessToken, self::jsonEncode($data));
        $this->log($result);
        if ($result) {
            $json = json_decode($result, true);
            if (!$json || !empty($json['errcode'])) {
                $this->errCode = $json['errcode'];
                $this->errMsg  = $json['errmsg'];
                return false;
            }
            return $json;
        }
        return false;
    }

    /**
     * [updateShakeAroundPage 编辑摇一摇出来的页面信息，包括在摇一摇页面出现的主标题、副标题、图片和点击进去的超链接。]
     * @param int $page_id
     * @param string $title 在摇一摇页面展示的主标题，不超过6 个字
     * @param string $description 在摇一摇页面展示的副标题，不超过7 个字
     * @param string $icon_url 在摇一摇页面展示的图片， 格式限定为：jpg,jpeg,png,gif; 建议120*120 ， 限制不超过200*200
     * @param string $page_url 跳转链接
     * @param string $comment 页面的备注信息，不超过15 个字,可不填
     * @return boolean|mixed
     * 正确返回JSON 数据示例:
     * {
    "data": {
    "page_id": 28840 //编辑页面的页面ID
    }
    "errcode": 0,
    "errmsg": "success."
    }
     * @access public
     * @author polo<gao.bo168@gmail.com>
     * @version 2015-3-25 下午3:02:51
     * @copyright Show More
     */
    public function updateShakeAroundPage($page_id,$title,$description,$icon_url,$page_url,$comment=''){
        if (!$this->accessToken && !$this->checkAuth()) return false;
        $data = array(
            "page_id" => $page_id,
            "title" => $title,
            "description" => $description,
            "icon_url" => $icon_url,
            "page_url" => $page_url,
            "comment" => $comment
        );
        $result = $this->httpPost(self::API_BASE_URL_PREFIX . self::SHAKEAROUND_PAGE_UPDATE . 'access_token=' . $this->accessToken, self::jsonEncode($data));
        $this->log($result);
        if ($result) {
            $json = json_decode($result, true);
            if (!$json || !empty($json['errcode'])) {
                $this->errCode = $json['errcode'];
                $this->errMsg  = $json['errmsg'];
                return false;
            }
            return $json;
        }
        return false;
    }

    /**
     * [searchShakeAroundPage 查询已有的页面，包括在摇一摇页面出现的主标题、副标题、图片和点击进去的超链接。
     * 提供两种查询方式，①可指定页面ID 查询，②也可批量拉取页面列表。]
     * @param array $page_ids
     * @param int $begin
     * @param int $count
     * ①需要查询指定页面时:
     * {
    "page_ids":[12345, 23456, 34567]
    }
     * +-------------------------------------------------------------------------------------------------------------
     * ②需要分页查询或者指定范围内的页面时:
     * {
    "begin": 0,
    "count": 3
    }
     * +-------------------------------------------------------------------------------------------------------------
     * @return boolean|mixed
     * 正确返回JSON 数据示例:
    {
    "data": {
    "pages": [
    {
    "comment": "just for test",
    "description": "test",
    "icon_url": "https://www.baidu.com/img/bd_logo1.png",
    "page_id": 28840,
    "page_url": "http://xw.qq.com/testapi1",
    "title": "测试1"
    },
    {
    "comment": "just for test",
    "description": "test",
    "icon_url": "https://www.baidu.com/img/bd_logo1.png",
    "page_id": 28842,
    "page_url": "http://xw.qq.com/testapi2",
    "title": "测试2"
    }
    ],
    "total_count": 2
    },
    "errcode": 0,
    "errmsg": "success."
    }
     *字段说明:
     *total_count 商户名下的页面总数
     *page_id 摇周边页面唯一ID
     *title 在摇一摇页面展示的主标题
     *description 在摇一摇页面展示的副标题
     *icon_url 在摇一摇页面展示的图片
     *page_url 跳转链接
     *comment 页面的备注信息
     * @access public
     * @author polo<gao.bo168@gmail.com>
     * @version 2015-3-25 下午3:12:17
     * @copyright Show More
     */
    public function searchShakeAroundPage($page_ids=array(),$begin=0,$count=1){
        if (!$this->accessToken && !$this->checkAuth()) return false;
        if(!empty($page_ids)){
            $data = array(
                'page_ids' => $page_ids
            );
        }else{
            $data = array(
                'begin' => $begin,
                'count' => $count
            );
        }
        $result = $this->httpPost(self::API_BASE_URL_PREFIX . self::SHAKEAROUND_PAGE_SEARCH . 'access_token=' . $this->accessToken, self::jsonEncode($data));
        $this->log($result);
        if ($result) {
            $json = json_decode($result, true);
            if (!$json || !empty($json['errcode'])) {
                $this->errCode = $json['errcode'];
                $this->errMsg  = $json['errmsg'];
                return false;
            }
            return $json;
        }
        return false;
    }

    /**
     * [deleteShakeAroundPage 删除已有的页面，包括在摇一摇页面出现的主标题、副标题、图片和点击进去的超链接。
     * 只有页面与设备没有关联关系时，才可被删除。]
     * @param array $page_ids
     * {
    "page_ids":[12345,23456,34567]
    }
     * @return boolean|mixed
     * 正确返回JSON 数据示例:
     * {
    "data": {
    },
    "errcode": 0,
    "errmsg": "success."
    }
     * @access public
     * @author polo<gao.bo168@gmail.com>
     * @version 2015-3-25 下午3:23:00
     * @copyright Show More
     */
    public function deleteShakeAroundPage($page_ids=array()){
        if (!$this->accessToken && !$this->checkAuth()) return false;
        $data = array(
            'page_ids' => $page_ids
        );
        $result = $this->httpPost(self::API_BASE_URL_PREFIX . self::SHAKEAROUND_PAGE_DELETE . 'access_token=' . $this->accessToken, self::jsonEncode($data));
        $this->log($result);
        if ($result) {
            $json = json_decode($result, true);
            if (!$json || !empty($json['errcode'])) {
                $this->errCode = $json['errcode'];
                $this->errMsg  = $json['errmsg'];
                return false;
            }
            return $json;
        }
        return false;
    }

    /**
     * [getShakeInfoShakeAroundUser 获取设备信息，包括UUID、major、minor，以及距离、openID 等信息。]
     * @param string $ticket 摇周边业务的ticket，可在摇到的URL 中得到，ticket生效时间为30 分钟
     * @return boolean|mixed
     * 正确返回JSON 数据示例:
     * {
    "data": {
    "page_id ": 14211,
    "beacon_info": {
    "distance": 55.00620700469034,
    "major": 10001,
    "minor": 19007,
    "uuid": "FDA50693-A4E2-4FB1-AFCF-C6EB07647825"
    },
    "openid": "oVDmXjp7y8aG2AlBuRpMZTb1-cmA"
    },
    "errcode": 0,
    "errmsg": "success."
    }
     * 字段说明:
     * beacon_info 设备信息，包括UUID、major、minor，以及距离
     * UUID、major、minor UUID、major、minor
     * distance Beacon 信号与手机的距离
     * page_id 摇周边页面唯一ID
     * openid 商户AppID 下用户的唯一标识
     * poi_id 门店ID，有的话则返回，没有的话不会在JSON 格式内
     * @access public
     * @author polo<gao.bo168@gmail.com>
     * @version 2015-3-25 下午3:28:20
     * @copyright Show More
     */
    public function getShakeInfoShakeAroundUser($ticket){
        if (!$this->accessToken && !$this->checkAuth()) return false;
        $data = array('ticket' => $ticket);
        $result = $this->httpPost(self::API_BASE_URL_PREFIX . self::SHAKEAROUND_USER_GETSHAKEINFO . 'access_token=' . $this->accessToken, self::jsonEncode($data));
        $this->log($result);
        if ($result) {
            $json = json_decode($result, true);
            if (!$json || !empty($json['errcode'])) {
                $this->errCode = $json['errcode'];
                $this->errMsg  = $json['errmsg'];
                return false;
            }
            return $json;
        }
        return false;
    }

    /**
     * [deviceShakeAroundStatistics 以设备为维度的数据统计接口。
     * 查询单个设备进行摇周边操作的人数、次数，点击摇周边消息的人数、次数；查询的最长时间跨度为30天。]
     * @param int $device_id 设备编号，若填了UUID、major、minor，即可不填设备编号，二者选其一
     * @param int $begin_date 起始日期时间戳，最长时间跨度为30 天
     * @param int $end_date 结束日期时间戳，最长时间跨度为30 天
     * @param string $uuid UUID、major、minor，三个信息需填写完成，若填了设备编辑，即可不填此信息，二者选其一
     * @param int $major
     * @param int $minor
     * @return boolean|mixed
     * 正确返回JSON 数据示例:
     * {
    "data": [
    {
    "click_pv": 0,
    "click_uv": 0,
    "ftime": 1425052800,
    "shake_pv": 0,
    "shake_uv": 0
    },
    {
    "click_pv": 0,
    "click_uv": 0,
    "ftime": 1425139200,
    "shake_pv": 0,
    "shake_uv": 0
    }
    ],
    "errcode": 0,
    "errmsg": "success."
    }
     * 字段说明:
     * ftime 当天0 点对应的时间戳
     * click_pv 点击摇周边消息的次数
     * click_uv 点击摇周边消息的人数
     * shake_pv 摇周边的次数
     * shake_uv 摇周边的人数
     * @access public
     * @author polo<gao.bo168@gmail.com>
     * @version 2015-4-21 00:39:00
     * @copyright Show More
     */
    public function deviceShakeAroundStatistics($device_id,$begin_date,$end_date,$uuid='',$major=0,$minor=0){
        if (!$this->accessToken && !$this->checkAuth()) return false;
        if(!$device_id){
            if(!$uuid || !$major || !$minor){
                return false;
            }
            $device_identifier = array(
                'uuid' => $uuid,
                'major' => $major,
                'minor' => $minor
            );
        }else{
            $device_identifier = array(
                'device_id' => $device_id
            );
        }
        $data = array(
            'device_identifier' => $device_identifier,
            'begin_date' => $begin_date,
            'end_date' => $end_date
        );
        $result = $this->httpPost(self::API_BASE_URL_PREFIX . self::SHAKEAROUND_STATISTICS_DEVICE . 'access_token=' . $this->accessToken, self::jsonEncode($data));
        $this->log($result);
        if ($result) {
            $json = json_decode($result, true);
            if (!$json || !empty($json['errcode'])) {
                $this->errCode = $json['errcode'];
                $this->errMsg  = $json['errmsg'];
                return false;
            }
            return $json;
        }
        return false;
    }


    /**
     * [pageShakeAroundStatistics 以页面为维度的数据统计接口。
     * 查询单个页面通过摇周边摇出来的人数、次数，点击摇周边页面的人数、次数；查询的最长时间跨度为30天。]
     * @param int $page_id 指定页面的ID
     * @param int $begin_date 起始日期时间戳，最长时间跨度为30 天
     * @param int $end_date 结束日期时间戳，最长时间跨度为30 天
     * @return boolean|mixed
     * 正确返回JSON 数据示例:
     * {
    "data": [
    {
    "click_pv": 0,
    "click_uv": 0,
    "ftime": 1425052800,
    "shake_pv": 0,
    "shake_uv": 0
    },
    {
    "click_pv": 0,
    "click_uv": 0,
    "ftime": 1425139200,
    "shake_pv": 0,
    "shake_uv": 0
    }
    ],
    "errcode": 0,
    "errmsg": "success."
    }
     * 字段说明:
     * ftime 当天0 点对应的时间戳
     * click_pv 点击摇周边消息的次数
     * click_uv 点击摇周边消息的人数
     * shake_pv 摇周边的次数
     * shake_uv 摇周边的人数
     * @author binsee<binsee@163.com>
     * @version 2015-4-21 00:43:00
     */
    public function pageShakeAroundStatistics($page_id,$begin_date,$end_date){
        if (!$this->accessToken && !$this->checkAuth()) return false;
        $data = array(
            'page_id' => $page_id,
            'begin_date' => $begin_date,
            'end_date' => $end_date
        );
        $result = $this->httpPost(self::API_BASE_URL_PREFIX . self::SHAKEAROUND_STATISTICS_DEVICE . 'access_token=' . $this->accessToken, self::jsonEncode($data));
        $this->log($result);
        if ($result) {
            $json = json_decode($result, true);
            if (!$json || !empty($json['errcode'])) {
                $this->errCode = $json['errcode'];
                $this->errMsg  = $json['errmsg'];
                return false;
            }
            return $json;
        }
        return false;
    }

    /**
     * 根据订单ID获取订单详情
     * @param string $order_id 订单ID
     * @return array|bool order
     */
    public function getOrderByID($order_id){
        if (!$this->accessToken && !$this->checkAuth()) return false;
        if (!$order_id) return false;

        $data = array(
            'order_id'=>$order_id
        );
        $result = $this->httpPost(self::API_BASE_URL_PREFIX.self::MERCHANT_ORDER_GETBYID.'access_token='.$this->accessToken, self::jsonEncode($data));
        if ($result)
        {
            $json = json_decode($result,true);
            if (isset($json['errcode']) && $json['errcode']) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                return false;
            }
            return $json['order'];
        }
        return false;
    }

    /**
     * 根据订单状态/创建时间获取订单详情
     * @param int $status 订单状态(不带该字段-全部状态, 2-待发货, 3-已发货, 5-已完成, 8-维权中, )
     * @param int $begintime 订单创建时间起始时间(不带该字段则不按照时间做筛选)
     * @param int $endtime 订单创建时间终止时间(不带该字段则不按照时间做筛选)
     * @return array|bool order list
     */
    public function getOrderByFilter($status = null, $begintime = null, $endtime = null){
        if (!$this->accessToken && !$this->checkAuth()) return false;

        $data = array();

        $valid_status = array(2, 3, 5, 8);
        if (is_numeric($status) && in_array($status, $valid_status)) {
            $data['status'] = $status;
        }

        if (is_numeric($begintime) && is_numeric($endtime)) {
            $data['begintime'] = $begintime;
            $data['endtime'] = $endtime;
        }
        $result = $this->httpPost(self::API_BASE_URL_PREFIX.self::MERCHANT_ORDER_GETBYFILTER.'access_token='.$this->accessToken, self::jsonEncode($data));
        if ($result)
        {
            $json = json_decode($result,true);
            if (isset($json['errcode']) && $json['errcode']) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                return false;
            }
            return $json['order_list'];
        }
        return false;
    }

    /**
     * 设置订单发货信息
     * @param string $order_id 订单 ID
     * @param int $need_delivery 商品是否需要物流(0-不需要，1-需要)
     * @param string $delivery_company 物流公司 ID
     * @param string $delivery_track_no 运单 ID
     * @param int $is_others 是否为 6.4.5 表之外的其它物流公司(0-否，1-是)
     * @return bool
     */
    public function setOrderDelivery($order_id, $need_delivery = 0, $delivery_company = null, $delivery_track_no = null, $is_others = 0){
        if (!$this->accessToken && !$this->checkAuth()) return false;
        if (!$order_id) return false;

        $data = array();
        $data['order_id'] = $order_id;
        if ($need_delivery) {
            $data['delivery_company'] = $delivery_company;
            $data['delivery_track_no'] = $delivery_track_no;
            $data['is_others'] = $is_others;
        }
        else {
            $data['need_delivery'] = $need_delivery;
        }

        $result = $this->httpPost(self::API_BASE_URL_PREFIX.self::MERCHANT_ORDER_SETDELIVERY.'access_token='.$this->accessToken, self::jsonEncode($data));
        if ($result)
        {
            $json = json_decode($result,true);
            if (isset($json['errcode']) && $json['errcode']) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                return false;
            }
            return true;
        }
        return false;
    }

    /**
     * 关闭订单
     * @param string $order_id 订单 ID
     * @return bool
     */
    public function closeOrder($order_id){
        if (!$this->accessToken && !$this->checkAuth()) return false;
        if (!$order_id) return false;

        $data = array(
            'order_id'=>$order_id
        );

        $result = $this->httpPost(self::API_BASE_URL_PREFIX.self::MERCHANT_ORDER_CLOSE.'access_token='.$this->accessToken, self::jsonEncode($data));
        if ($result)
        {
            $json = json_decode($result,true);
            if (isset($json['errcode']) && $json['errcode']) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                return false;
            }
            return true;
        }
        return false;
    }

    private function parseSkuInfo($skuInfo) {
        $skuInfo = str_replace("\$", "", $skuInfo);
        $matches = explode(";", $skuInfo);

        $result = array();
        foreach ($matches as $matche) {
            $arrs = explode(":", $matche);
            $result[$arrs[0]] = $arrs[1];
        }

        return $result;
    }

    /**
     * 获取订单SkuInfo - 订单付款通知
     * 当Event为 merchant_order(订单付款通知)
     * @return array|boolean
     */
    public function getRevOrderSkuInfo(){
        if (isset($this->receive['SkuInfo']))     //订单 SkuInfo
            return $this->parseSkuInfo($this->receive['SkuInfo']);
        else
            return false;
    }
}