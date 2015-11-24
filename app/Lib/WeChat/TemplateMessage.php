<?php
namespace App\Lib\WeChat;
/**
 * 模板消息接口
 */
/**
模板消息仅用于公众号向用户发送重要的服务通知，只能用于符合其要求的服务场景中，如信用卡刷卡通知，商品购买成功通知等。不支持广告等营销类消息以及其它所有可能对用户造成骚扰的消息。

关于使用规则，请注意：
1、所有服务号都可以在功能->添加功能插件处看到申请模板消息功能的入口，但只有认证后的服务号才可以申请模板消息的使用权限并获得该权限；
2、需要选择公众账号服务所处的2个行业，每月可更改1次所选行业；
3、在所选择行业的模板库中选用已有的模板进行调用；
4、每个账号可以同时使用15个模板。
5、当前每个模板的日调用上限为100000次。

关于接口文档，请注意：
1、模板消息调用时主要需要模板ID和模板中各参数的赋值内容；
2、模板中参数内容必须以".DATA"结尾，否则视为保留字；
3、模板保留符号"{{ }}"。
 */

class TemplateMessage {
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

    /**
     * 设置所属行业
     * $industryId1 公众号模板消息所属行业编号 请打开连接查看行业编号 http://mp.weixin.qq.com/wiki/17/304c1885ea66dbedf7dc170d84999a9d.html#.E8.AE.BE.E7.BD.AE.E6.89.80.E5.B1.9E.E8.A1.8C.E4.B8.9A
     * $industryId2 公众号模板消息所属行业编号
     */
    public static function setIndustry($industryId1, $industryId2) {
        $queryUrl                 = 'https://api.weixin.qq.com/cgi-bin/template/api_set_industry?access_token='.AccessToken::getAccessToken();
        $template                 = array();
        $template['industry_id1'] = $industryId1.'';
        $template['industry_id2'] = $industryId2.'';
        $template                 = json_encode($template);
        return Curl::post($queryUrl, $template);
    }

    /**
     * 获得模板ID
     * $templateIdShort 模板库中模板的编号，有“TM**”和“OPENTMTM**”等形式
     *
     * @return array("errcode"=>0, "errmsg"=>"ok", "template_id":"Doclyl5uP7Aciu-qZ7mJNPtWkbkYnWBWVja26EGbNyk")  "errcode"是0则表示没有出错
     */
    public static function getTemplateId($templateIdShort) {
        $queryUrl                      = 'https://api.weixin.qq.com/cgi-bin/template/api_add_template?access_token='.AccessToken::getAccessToken();
        $template                      = array();
        $template['template_id_short'] = $templateIdShort;
        $template                      = json_encode($template);
        return Curl::post($queryUrl, $template);
    }

    /**
     * 向用户推送模板消息
     * @param $data = array(
     *                  'first'=>array('value'=>'您好，您已成功消费。', 'color'=>'#0A0A0A')
     *                  'keynote1'=>array('value'=>'巧克力', 'color'=>'#CCCCCC')
     *                  'keynote2'=>array('value'=>'39.8元', 'color'=>'#CCCCCC')
     *                  'keynote3'=>array('value'=>'2014年9月16日', 'color'=>'#CCCCCC')
     *                  'keynote3'=>array('value'=>'欢迎再次购买。', 'color'=>'#173177')
     * );
     * @param $touser 接收方的OpenId。
     * @param $templateId 模板Id。在公众平台线上模板库中选用模板获得ID
     * @param $url URL
     * @param string $topcolor 顶部颜色， 可以为空。默认是红色
     * @return array("errcode"=>0, "errmsg"=>"ok", "msgid"=>200228332} "errcode"是0则表示没有出错
     *
     * 注意：推送后用户到底是否成功接受，微信会向公众号推送一个消息。
     */
    public static function sendTemplateMessage($data, $touser, $templateId, $url, $topcolor='#FF0000') {
        $queryUrl                = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token='.AccessToken::getAccessToken();
        $template                = array();
        $template['touser']      = $touser;
        $template['template_id'] = $templateId;
        $template['url']         = $url;
        $template['topcolor']    = $topcolor;
        $template['data']        = $data;
        $template                = json_encode($template);
        return Curl::post($queryUrl, $template);
    }
}