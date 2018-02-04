<?php
namespace Module\WeChat\Domain\Model;

use Domain\Model\Model;


/**
 * 微信请求消息历史记录
 * @property integer $id
 * @property integer $wid
 * @property integer $rid
 * @property integer $kid
 * @property string $from
 * @property string $to
 * @property string $message
 * @property string $type
 * @property integer $created_at
 */
class MessageHistoryModel extends Model {
    /**
     * 微信请求信息
     */
    const TYPE_REQUEST = 'request';
    /**
     * 微信请求后的系统响应信息
     */
    const TYPE_RESPONSE = 'response';
    /**
     * 主动客服消息
     */
    const TYPE_CUSTOM = 'custom';
    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'wechat_message_history';
    }

    protected function rules() {
        return [
            'wid' => 'required|int',
            'rid' => 'required|int',
            'kid' => 'required|int',
            'from' => 'required|string:3-50',
            'to' => 'required|string:3-50',
            'message' => 'required',
            'type' => 'required|string:3-10',
            'created_at' => 'int',
        ];
    }


    /**
     * @inheritdoc
     */
    public function labels() {
        return [
            'id' => 'ID',
            'wid' => '所属微信公众号ID',
            'rid' => '相应规则ID',
            'kid' => '所属关键字ID',
            'from' => '请求用户ID',
            'to' => '相应用户ID',
            'message' => '消息体内容',
            'type' => '发送类型',
            'created_at' => '创建时间',
        ];
    }
}