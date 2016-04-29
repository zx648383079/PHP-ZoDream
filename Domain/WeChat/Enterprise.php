<?php
namespace Domain\WeChat;
/**
 * 企业号
 * Created by PhpStorm.
 * User: zx648
 * Date: 2016/3/9
 * Time: 10:53
 */
class Enterprise extends Core {

    const EVENT_ENTER_AGENT = 'enter_agent';   	//用户进入应用

    const API_URL_PREFIX = 'https://qyapi.weixin.qq.com/cgi-bin';

    const USER_CREATE_URL 		= '/user/create?';
    const USER_UPDATE_URL 		= '/user/update?';
    const USER_DELETE_URL 		= '/user/delete?';
    const USER_BATCHDELETE_URL 	= '/user/batchdelete?';
    const USER_LIST_URL 		= '/user/simplelist?';
    const USER_LIST_INFO_URL 	= '/user/list?';
    const USER_GETINFO_URL 		= '/user/getuserinfo?';
    const USER_INVITE_URL 		= '/invite/send?';
    const DEPARTMENT_CREATE_URL = '/department/create?';
    const DEPARTMENT_UPDATE_URL = '/department/update?';
    const DEPARTMENT_DELETE_URL = '/department/delete?';
    const DEPARTMENT_MOVE_URL 	= '/department/move?';
    const DEPARTMENT_LIST_URL 	= '/department/list?';
    const TAG_CREATE_URL 		= '/tag/create?';
    const TAG_UPDATE_URL 		= '/tag/update?';
    const TAG_DELETE_URL 		= '/tag/delete?';
    const TAG_GET_URL 			= '/tag/get?';
    const TAG_ADDUSER_URL 		= '/tag/addtagusers?';
    const TAG_DELUSER_URL 		= '/tag/deltagusers?';
    const TAG_LIST_URL 			= '/tag/list?';
    const AUTHSUCC_URL 			= '/user/authsucc?';
    const MASS_SEND_URL 		= '/message/send?';
    const TOKEN_GET_URL 		= '/gettoken?';
    const TICKET_GET_URL 		= '/get_jsapi_ticket?';

    protected $agentId;       //应用id   AgentID
    protected $agentIdXml;    //接收的应用id   AgentID
    protected $sendMsg;      //主动发送消息的内容


    /**
     * 获取微信服务器发来的信息
     */
    public function getRev() {
        parent::getRev();
        if (!empty($this->postXml) && !isset($this->receive['AgentID'])) {
            $this->receive['AgentID'] = $this->agentIdXml; //当前接收消息的应用id
        }
        return $this;
    }


    /**
     * 获取接收消息的应用id
     */
    public function getRevAgentID() {
        if (isset($this->receive['AgentID']))
            return $this->receive['AgentID'];
        else
            return false;
    }

    /**
     * 通用auth验证方法
     * @param string $appid
     * @param string $appsecret
     * @param string $token 手动指定access_token，非必要情况不建议用
     *
     * @return bool|mixed
     */
    public function checkAuth($appid='',$appsecret='',$token=''){
        if (!$appid || !$appsecret) {
            $appid = $this->appId;
            $appsecret = $this->appSecret;
        }
        if ($token) { //手动指定token，优先使用
            $this->accessToken = $token;
            return $this->accessToken;
        }

        $authname = 'qywechat_access_token'.$appid;
        if ($rs = $this->getCache($authname))  {
            $this->accessToken = $rs;
            return $rs;
        }

        $result = $this->httpGet(self::API_URL_PREFIX.self::TOKEN_GET_URL.'corpid='.$appid.'&corpsecret='.$appsecret);
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
            $this->setCache($authname,$this->accessToken,$expire);
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
    public function getJsTicket($appId='', $jsApiTicket=''){
        if (!$this->accessToken && !$this->checkAuth()) return false;
        if (!$appId) $appId = $this->appId;
        if ($jsApiTicket) { //手动指定token，优先使用
            $this->jsApiTicket = $jsApiTicket;
            return $this->jsApiTicket;
        }
        $authname = 'qywechat_jsapi_ticket'.$appId;
        if ($rs = $this->getCache($authname))  {
            $this->jsApiTicket = $rs;
            return $rs;
        }
        $result = $this->httpGet(self::API_URL_PREFIX.self::TICKET_GET_URL.'access_token='.$this->accessToken);
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
            $this->setCache($authname, $this->jsApiTicket, $expire);
            return $this->jsApiTicket;
        }
        return false;
    }


    /**
     * 创建菜单
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
     * type可以选择为以下几种，会收到相应类型的事件推送。请注意，3到8的所有事件，仅支持微信iPhone5.4.1以上版本，
     * 和Android5.4以上版本的微信用户，旧版本微信用户点击后将没有回应，开发者也不能正常接收到事件推送。
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
    public function createMenu($data, $agentid=''){
        if ($agentid=='') {
            $agentid=$this->agentId;
        }
        if (!$this->accessToken && !$this->checkAuth()) return false;
        $result = $this->httpPost(self::API_URL_PREFIX.self::MENU_CREATE_URL.'access_token='.$this->accessToken.'&agentid='.$agentid,self::jsonEncode($data));
        if ($result)
        {
            $json = json_decode($result,true);
            if (!$json || !empty($json['errcode']) || $json['errcode']!=0) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                return false;
            }
            return true;
        }
        return false;
    }

    /**
     * 获取菜单
     * @return array('menu'=>array(....s))
     */
    public function getMenu($agentid=''){
        if ($agentid=='') {
            $agentid = $this->agentId;
        }
        if (!$this->accessToken && !$this->checkAuth()) return false;
        $result = $this->httpGet(self::API_URL_PREFIX.self::MENU_GET_URL.'access_token='.$this->accessToken.'&agentid='.$agentid);
        if ($result)
        {
            $json = json_decode($result,true);
            if (!$json || isset($json['errcode']) || $json['errcode']!=0) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                return false;
            }
            return $json;
        }
        return false;
    }

    /**
     * 删除菜单
     * @param string $agentId
     * @return bool
     */
    public function deleteMenu($agentId = ''){
        if ($agentId == '') {
            $agentId = $this->agentId;
        }
        if (!$this->accessToken && !$this->checkAuth()) return false;
        $result = $this->httpGet(self::API_URL_PREFIX.self::MENU_DELETE_URL.'access_token='.$this->accessToken.'&agentid='.$agentId);
        if ($result) {
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
     * 创建部门
     * @param array $data 	结构体为:
     * array (
     *     "name" => "邮箱产品组",   //部门名称
     *     "parentid" => "1"         //父部门id
     *     "order" =>  "1",            //(非必须)在父部门中的次序。从1开始，数字越大排序越靠后
     * )
     * @return boolean|array
     * 成功返回结果
     * {
     *   "errcode": 0,        //返回码
     *   "errmsg": "created",  //对返回码的文本描述内容
     *   "id": 2               //创建的部门id。
     * }
     */
    public function createDepartment($data){
        if (!$this->accessToken && !$this->checkAuth()) return false;
        $result = $this->httpPost(self::API_URL_PREFIX.self::DEPARTMENT_CREATE_URL.'access_token='.$this->accessToken,self::jsonEncode($data));
        if ($result)
        {
            $json = json_decode($result,true);
            if (!$json || !empty($json['errcode']) || $json['errcode']!=0) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                return false;
            }
            return $json;
        }
        return false;
    }


    /**
     * 更新部门
     * @param array $data 	结构体为:
     * array(
     *     "id" => "1"               //(必须)部门id
     *     "name" =>  "邮箱产品组",   //(非必须)部门名称
     *     "parentid" =>  "1",         //(非必须)父亲部门id。根部门id为1
     *     "order" =>  "1",            //(非必须)在父部门中的次序。从1开始，数字越大排序越靠后
     * )
     * @return boolean|array 成功返回结果
     * {
     *   "errcode": 0,        //返回码
     *   "errmsg": "updated"  //对返回码的文本描述内容
     * }
     */
    public function updateDepartment($data){
        if (!$this->accessToken && !$this->checkAuth()) return false;
        $result = $this->httpPost(self::API_URL_PREFIX.self::DEPARTMENT_UPDATE_URL.'access_token='.$this->accessToken,self::jsonEncode($data));
        if ($result)
        {
            $json = json_decode($result,true);
            if (!$json || !empty($json['errcode']) || $json['errcode']!=0) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                return false;
            }
            return $json;
        }
        return false;
    }

    /**
     * 删除部门
     * @param $id
     * @return boolean|array 成功返回结果
     * {
     *   "errcode": 0,        //返回码
     *   "errmsg": "deleted"  //对返回码的文本描述内容
     * }
     */
    public function deleteDepartment($id){
        if (!$this->accessToken && !$this->checkAuth()) return false;
        $result = $this->httpGet(self::API_URL_PREFIX.self::DEPARTMENT_DELETE_URL.'access_token='.$this->accessToken.'&id='.$id);
        if ($result)
        {
            $json = json_decode($result,true);
            if (!$json || !empty($json['errcode']) || $json['errcode']!=0) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                return false;
            }
            return $json;
        }
        return false;
    }

    /**
     * 移动部门
     * @param $data
     * array(
     *    "department_id" => "5",	//所要移动的部门
     *    "to_parentid" => "2",		//想移动到的父部门节点，根部门为1
     *    "to_position" => "1"		//(非必须)想移动到的父部门下的位置，1表示最上方，往后位置为2，3，4，以此类推，默认为1
     * )
     * @return boolean|array 成功返回结果
     * {
     *   "errcode": 0,        //返回码
     *   "errmsg": "ok"  //对返回码的文本描述内容
     * }
     */
    public function moveDepartment($data){
        if (!$this->accessToken && !$this->checkAuth()) return false;
        $result = $this->httpPost(self::API_URL_PREFIX.self::DEPARTMENT_MOVE_URL.'access_token='.$this->accessToken,self::jsonEncode($data));
        if ($result)
        {
            $json = json_decode($result,true);
            if (!$json || !empty($json['errcode']) || $json['errcode']!=0) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                return false;
            }
            return $json;
        }
        return false;
    }

    /**
     * 获取部门列表
     * @return boolean|array	 成功返回结果
     * {
     *    "errcode": 0,
     *    "errmsg": "ok",
     *    "department": [          //部门列表数据。以部门的order字段从小到大排列
     *        {
     *            "id": 1,
     *            "name": "广州研发中心",
     *            "parentid": 0,
     *            "order": 40
     *        },
     *       {
     *          "id": 2
     *          "name": "邮箱产品部",
     *          "parentid": 1,
     *          "order": 40
     *       }
     *    ]
     * }
     */
    public function getDepartment(){
        if (!$this->accessToken && !$this->checkAuth()) return false;
        $result = $this->httpGet(self::API_URL_PREFIX.self::DEPARTMENT_LIST_URL.'access_token='.$this->accessToken);
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
     * 创建成员
     * @param array $data 	结构体为:
     * array(
     *    "userid" => "zhangsan",
     *    "name" => "张三",
     *    "department" => [1, 2],
     *    "position" => "产品经理",
     *    "mobile" => "15913215421",
     *    "gender" => 1,     //性别。gender=0表示男，=1表示女
     *    "tel" => "62394",
     *    "email" => "zhangsan@gzdev.com",
     *    "weixinid" => "zhangsan4dev"
     * )
     * @return boolean|array
     * 成功返回结果
     * {
     *   "errcode": 0,        //返回码
     *   "errmsg": "created",  //对返回码的文本描述内容
     * }
     */
    public function createUser($data){
        if (!$this->accessToken && !$this->checkAuth()) return false;
        $result = $this->httpPost(self::API_URL_PREFIX.self::USER_CREATE_URL.'access_token='.$this->accessToken,self::jsonEncode($data));
        if ($result)
        {
            $json = json_decode($result,true);
            if (!$json || !empty($json['errcode']) || $json['errcode']!=0) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                return false;
            }
            return $json;
        }
        return false;
    }


    /**
     * 更新成员
     * @param array $data 	结构体为:
     * array(
     *    "userid" => "zhangsan",
     *    "name" => "张三",
     *    "department" => [1, 2],
     *    "position" => "产品经理",
     *    "mobile" => "15913215421",
     *    "gender" => 1,     //性别。gender=0表示男，=1表示女
     *    "tel" => "62394",
     *    "email" => "zhangsan@gzdev.com",
     *    "weixinid" => "zhangsan4dev"
     * )
     * @return boolean|array 成功返回结果
     * {
     *   "errcode": 0,        //返回码
     *   "errmsg": "updated"  //对返回码的文本描述内容
     * }
     */
    public function updateUser($data){
        if (!$this->accessToken && !$this->checkAuth()) return false;
        $result = $this->httpPost(self::API_URL_PREFIX.self::USER_UPDATE_URL.'access_token='.$this->accessToken,self::jsonEncode($data));
        if ($result)
        {
            $json = json_decode($result,true);
            if (!$json || !empty($json['errcode']) || $json['errcode']!=0) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                return false;
            }
            return $json;
        }
        return false;
    }

    /**
     * 删除成员
     * @param string $userid  员工UserID。对应管理端的帐号
     * @return boolean|array 成功返回结果
     * {
     *   "errcode": 0,        //返回码
     *   "errmsg": "deleted"  //对返回码的文本描述内容
     * }
     */
    public function deleteUser($userid){
        if (!$this->accessToken && !$this->checkAuth()) return false;
        $result = $this->httpGet(self::API_URL_PREFIX.self::USER_DELETE_URL.'access_token='.$this->accessToken.'&userid='.$userid);
        if ($result)
        {
            $json = json_decode($result,true);
            if (!$json || !empty($json['errcode']) || $json['errcode']!=0) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                return false;
            }
            return $json;
        }
        return false;
    }

    /**
     * 批量删除成员
     * @param $userids
     * @return array|bool 成功返回结果
     * {
     * "errcode": 0,        //返回码
     * "errmsg": "deleted"  //对返回码的文本描述内容
     * }
     * @internal param array $userid 员工UserID数组。对应管理端的帐号
     * {
     *     'userid1',
     *     'userid2',
     *     'userid3',
     * }
     */
    public function deleteUsers($userids){
        if (!$userids) return false;
        $data = array('useridlist'=>$userids);
        if (!$this->accessToken && !$this->checkAuth()) return false;
        $result = $this->httpPost(self::API_URL_PREFIX.self::USER_BATCHDELETE_URL.'access_token='.$this->accessToken,self::jsonEncode($data));
        if ($result)
        {
            $json = json_decode($result,true);
            if (!$json || !empty($json['errcode']) || $json['errcode']!=0) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                return false;
            }
            return $json;
        }
        return false;
    }

    /**
     * 获取部门成员
     * @param string $department_id   部门id
     * @param $fetch_child     1/0：是否递归获取子部门下面的成员
     * @param $status          0获取全部员工，1获取已关注成员列表，2获取禁用成员列表，4获取未关注成员列表。status可叠加
     * @return boolean|array	 成功返回结果
     * {
     *    "errcode": 0,
     *    "errmsg": "ok",
     *    "userlist": [
     *            {
     *                   "userid": "zhangsan",
     *                   "name": "李四"
     *            }
     *      ]
     * }
     */
    public function getUserList($department_id,$fetch_child=0,$status=0){
        if (!$this->accessToken && !$this->checkAuth()) return false;
        $result = $this->httpGet(self::API_URL_PREFIX.self::USER_LIST_URL.'access_token='.$this->accessToken
            .'&department_id='.$department_id.'&fetch_child='.$fetch_child.'&status='.$status);
        if ($result)
        {
            $json = json_decode($result,true);
            if (!$json || !empty($json['errcode']) || $json['errcode']!=0) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                return false;
            }
            return $json;
        }
        return false;
    }

    /**
     * 获取部门成员详情
     * @param string $department_id   部门id
     * @param $fetch_child     1/0：是否递归获取子部门下面的成员
     * @param $status          0获取全部员工，1获取已关注成员列表，2获取禁用成员列表，4获取未关注成员列表。status可叠加
     * @return boolean|array	 成功返回结果
     * {
     *    "errcode": 0,
     *    "errmsg": "ok",
     *    "userlist": [
     *            {
     *                   "userid": "zhangsan",
     *                   "name": "李四",
     *                   "department": [1, 2],
     *                   "position": "后台工程师",
     *                   "mobile": "15913215421",
     *                   "gender": 1,     //性别。gender=0表示男，=1表示女
     *                   "tel": "62394",
     *                   "email": "zhangsan@gzdev.com",
     *                   "weixinid": "lisifordev",        //微信号
     *                   "avatar": "http://wx.qlogo.cn/mmopen/ajNVdqHZLLA3W..../0",   //头像url。注：如果要获取小图将url最后的"/0"改成"/64"即可
     *                   "status": 1      //关注状态: 1=已关注，2=已冻结，4=未关注
     *                   "extattr": {"attrs":[{"name":"爱好","value":"旅游"},{"name":"卡号","value":"1234567234"}]}
     *            }
     *      ]
     * }
     */
    public function getUserListInfo($department_id,$fetch_child=0,$status=0){
        if (!$this->accessToken && !$this->checkAuth()) return false;
        $result = $this->httpGet(self::API_URL_PREFIX.self::USER_LIST_INFO_URL.'access_token='.$this->accessToken
            .'&department_id='.$department_id.'&fetch_child='.$fetch_child.'&status='.$status);
        if ($result)
        {
            $json = json_decode($result,true);
            if (!$json || !empty($json['errcode']) || $json['errcode']!=0) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                return false;
            }
            return $json;
        }
        return false;
    }

    /**
     * 根据code获取成员信息
     * 通过Oauth2.0或者设置了二次验证时获取的code，用于换取成员的UserId和DeviceId
     *
     * @param string $code Oauth2.0或者二次验证时返回的code值
     * @param int|string $agentid 跳转链接时所在的企业应用ID，未填则默认为当前配置的应用id
     * @return array|bool 成功返回数组
     * array(
     * 'UserId' => 'USERID',       //员工UserID
     * 'DeviceId' => 'DEVICEID'    //手机设备号(由微信在安装时随机生成)
     * )
     */
    public function getUserId($code,$agentid=0){
        if (!$agentid) $agentid=$this->agentId;
        if (!$this->accessToken && !$this->checkAuth()) return false;
        $result = $this->httpGet(self::API_URL_PREFIX.self::USER_GETINFO_URL.'access_token='.$this->accessToken.'&code='.$code.'&agentid='.$agentid);
        if ($result)
        {
            $json = json_decode($result,true);
            if (!$json || !empty($json['errcode']) || $json['errcode']!=0) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                return false;
            }
            return $json;
        }
        return false;
    }

    /**
     * 邀请成员关注
     * 向未关注企业号的成员发送关注邀请。认证号优先判断顺序weixinid>手机号>邮箱绑定>邮件；非认证号只能邮件邀请
     *
     * @param string $userid        用户的userid
     * @param string $invite_tips   推送到微信上的提示语（只有认证号可以使用）。当使用微信推送时，该字段默认为“请关注XXX企业号”，邮件邀请时，该字段无效。
     * @return boolean|array 成功返回数组
     * array(
     *     'errcode' => 0,
     *     'errmsg' => 'ok',
     *     'type' => 1         //邀请方式 1.微信邀请 2.邮件邀请
     * )
     */
    public function sendInvite($userid,$invite_tips=''){
        $data = array( 'userid' => $userid );
        if (!$invite_tips) {
            $data['invite_tips'] = $invite_tips;
        }
        if (!$this->accessToken && !$this->checkAuth()) return false;
        $result = $this->httpPost(self::API_URL_PREFIX.self::USER_INVITE_URL.'access_token='.$this->accessToken,self::jsonEncode($data));
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
     * 创建标签
     * @param array $data 	结构体为:
     * array(
     *    "tagname" => "UI"
     * )
     * @return boolean|array
     * 成功返回结果
     * {
     *   "errcode": 0,        //返回码
     *   "errmsg": "created",  //对返回码的文本描述内容
     *   "tagid": "1"
     * }
     */
    public function createTag($data){
        if (!$this->accessToken && !$this->checkAuth()) return false;
        $result = $this->httpPost(self::API_URL_PREFIX.self::TAG_CREATE_URL.'access_token='.$this->accessToken,self::jsonEncode($data));
        if ($result)
        {
            $json = json_decode($result,true);
            if (!$json || !empty($json['errcode']) || $json['errcode']!=0) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                return false;
            }
            return $json;
        }
        return false;
    }

    /**
     * 更新标签
     * @param array $data 	结构体为:
     * array(
     *    "tagid" => "1",
     *    "tagname" => "UI design"
     * )
     * @return boolean|array 成功返回结果
     * {
     *   "errcode": 0,        //返回码
     *   "errmsg": "updated"  //对返回码的文本描述内容
     * }
     */
    public function updateTag($data){
        if (!$this->accessToken && !$this->checkAuth()) return false;
        $result = $this->httpPost(self::API_URL_PREFIX.self::TAG_UPDATE_URL.'access_token='.$this->accessToken,self::jsonEncode($data));
        if ($result)
        {
            $json = json_decode($result,true);
            if (!$json || !empty($json['errcode']) || $json['errcode']!=0) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                return false;
            }
            return $json;
        }
        return false;
    }

    /**
     * 删除标签
     * @param string $tagid  标签TagID
     * @return boolean|array 成功返回结果
     * {
     *   "errcode": 0,        //返回码
     *   "errmsg": "deleted"  //对返回码的文本描述内容
     * }
     */
    public function deleteTag($tagid){
        if (!$this->accessToken && !$this->checkAuth()) return false;
        $result = $this->httpGet(self::API_URL_PREFIX.self::TAG_DELETE_URL.'access_token='.$this->accessToken.'&tagid='.$tagid);
        if ($result)
        {
            $json = json_decode($result,true);
            if (!$json || !empty($json['errcode']) || $json['errcode']!=0) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                return false;
            }
            return $json;
        }
        return false;
    }

    /**
     * 获取标签成员
     * @param string $tagid  标签TagID
     * @return boolean|array	 成功返回结果
     * {
     *    "errcode": 0,
     *    "errmsg": "ok",
     *    "userlist": [
     *          {
     *              "userid": "zhangsan",
     *              "name": "李四"
     *          }
     *      ]
     * }
     */
    public function getTag($tagid){
        if (!$this->accessToken && !$this->checkAuth()) return false;
        $result = $this->httpGet(self::API_URL_PREFIX.self::TAG_GET_URL.'access_token='.$this->accessToken.'&tagid='.$tagid);
        if ($result)
        {
            $json = json_decode($result,true);
            if (!$json || !empty($json['errcode']) || $json['errcode']!=0) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                return false;
            }
            return $json;
        }
        return false;
    }

    /**
     * 增加标签成员
     * @param array $data 	结构体为:
     * array (
     *    "tagid" => "1",
     *    "userlist" => array(    //企业员工ID列表
     *         "user1",
     *         "user2"
     *     )
     * )
     * @return boolean|array
     * 成功返回结果
     * {
     *   "errcode": 0,        //返回码
     *   "errmsg": "ok",  //对返回码的文本描述内容
     *   "invalidlist"："usr1|usr2|usr"     //若部分userid非法，则会有此段。不在权限内的员工ID列表，以“|”分隔
     * }
     */
    public function addTagUser($data){
        if (!$this->accessToken && !$this->checkAuth()) return false;
        $result = $this->httpPost(self::API_URL_PREFIX.self::TAG_ADDUSER_URL.'access_token='.$this->accessToken,self::jsonEncode($data));
        if ($result)
        {
            $json = json_decode($result,true);
            if (!$json || !empty($json['errcode']) || $json['errcode']!=0) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                return false;
            }
            return $json;
        }
        return false;
    }

    /**
     * 删除标签成员
     * @param array $data 	结构体为:
     * array (
     *    "tagid" => "1",
     *    "userlist" => array(    //企业员工ID列表
     *         "user1",
     *         "user2"
     *     )
     * )
     * @return boolean|array
     * 成功返回结果
     * {
     *   "errcode": 0,        //返回码
     *   "errmsg": "deleted",  //对返回码的文本描述内容
     *   "invalidlist"："usr1|usr2|usr"     //若部分userid非法，则会有此段。不在权限内的员工ID列表，以“|”分隔
     * }
     */
    public function delTagUser($data){
        if (!$this->accessToken && !$this->checkAuth()) return false;
        $result = $this->httpPost(self::API_URL_PREFIX.self::TAG_DELUSER_URL.'access_token='.$this->accessToken,self::jsonEncode($data));
        if ($result)
        {
            $json = json_decode($result,true);
            if (!$json || !empty($json['errcode']) || $json['errcode']!=0) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                return false;
            }
            return $json;
        }
        return false;
    }

    /**
     * 获取标签列表
     * @return boolean|array	 成功返回数组结果，这里附上json样例
     * {
     *    "errcode": 0,
     *    "errmsg": "ok",
     *    "taglist":[
     *       {"tagid":1,"tagname":"a"},
     *       {"tagid":2,"tagname":"b"}
     *    ]
     * }
     */
    public function getTagList(){
        if (!$this->accessToken && !$this->checkAuth()) return false;
        $result = $this->httpGet(self::API_URL_PREFIX.self::TAG_LIST_URL.'access_token='.$this->accessToken);
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
     * 主动发送信息接口
     * @param array $data 	结构体为:
     * array(
     *         "touser" => "UserID1|UserID2|UserID3",
     *         "toparty" => "PartyID1|PartyID2 ",
     *         "totag" => "TagID1|TagID2 ",
     *         "safe":"0"			//是否为保密消息，对于news无效
     *         "agentid" => "001",	//应用id
     *         "msgtype" => "text",  //根据信息类型，选择下面对应的信息结构体
     *
     *         "text" => array(
     *                 "content" => "Holiday Request For Pony(http://xxxxx)"
     *         ),
     *
     *         "image" => array(
     *                 "media_id" => "MEDIA_ID"
     *         ),
     *
     *         "voice" => array(
     *                 "media_id" => "MEDIA_ID"
     *         ),
     *
     *         " video" => array(
     *                 "media_id" => "MEDIA_ID",
     *                 "title" => "Title",
     *                 "description" => "Description"
     *         ),
     *
     *         "file" => array(
     *                 "media_id" => "MEDIA_ID"
     *         ),
     *
     *         "news" => array(			//不支持保密
     *                 "articles" => array(    //articles  图文消息，一个图文消息支持1到10个图文
     *                     array(
     *                         "title" => "Title",             //标题
     *                         "description" => "Description", //描述
     *                         "url" => "URL",                 //点击后跳转的链接。可根据url里面带的code参数校验员工的真实身份。
     *                         "picurl" => "PIC_URL",          //图文消息的图片链接,支持JPG、PNG格式，较好的效果为大图640*320，
     *                                                         //小图80*80。如不填，在客户端不显示图片
     *                     ),
     *                 )
     *         ),
     *
     *         "mpnews" => array(
     *                 "articles" => array(    //articles  图文消息，一个图文消息支持1到10个图文
     *                     array(
     *                         "title" => "Title",             //图文消息的标题
     *                         "thumb_media_id" => "id",       //图文消息缩略图的media_id
     *                         "author" => "Author",           //图文消息的作者(可空)
     *                         "content_source_url" => "URL",  //图文消息点击“阅读原文”之后的页面链接(可空)
     *                         "content" => "Content"          //图文消息的内容，支持html标签
     *                         "digest" => "Digest description",   //图文消息的描述
     *                         "show_cover_pic" => "0"         //是否显示封面，1为显示，0为不显示(可空)
     *                     ),
     *                 )
     *         )
     * )
     * 请查看官方开发文档中的 发送消息 -> 消息类型及数据格式
     *
     * @return boolean|array
     * 如果对应用或收件人、部门、标签任何一个无权限，则本次发送失败；
     * 如果收件人、部门或标签不存在，发送仍然执行，但返回无效的部分。
     * {
     *    "errcode": 0,
     *    "errmsg": "ok",
     *    "invaliduser": "UserID1",
     *    "invalidparty":"PartyID1",
     *    "invalidtag":"TagID1"
     * }
     */
    public function sendMessage($data){
        if (!$this->accessToken && !$this->checkAuth()) return false;
        $result = $this->httpPost(self::API_URL_PREFIX.self::MASS_SEND_URL.'access_token='.$this->accessToken,self::jsonEncode($data));
        if ($result)
        {
            $json = json_decode($result,true);
            if (!$json || !empty($json['errcode']) || $json['errcode']!=0) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                return false;
            }
            return $json;
        }
        return false;
    }

    /**
     * 二次验证
     * 企业在开启二次验证时，必须填写企业二次验证页面的url。
     * 当员工绑定通讯录中的帐号后，会收到一条图文消息，
     * 引导员工到企业的验证页面验证身份，企业在员工验证成功后，
     * 调用如下接口即可让员工关注成功。
     *
     * @param $userid
     * @return boolean|array 成功返回结果
     * {
     *   "errcode": 0,        //返回码
     *   "errmsg": "ok"  //对返回码的文本描述内容
     * }
     */
    public function authSucc($userid){
        if (!$this->accessToken && !$this->checkAuth()) return false;
        $result = $this->httpGet(self::API_URL_PREFIX.self::AUTHSUCC_URL.'access_token='.$this->accessToken.'&userid='.$userid);
        if ($result)
        {
            $json = json_decode($result,true);
            if (!$json || !empty($json['errcode']) || $json['errcode']!=0) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                return false;
            }
            return $json;
        }
        return false;
    }
}