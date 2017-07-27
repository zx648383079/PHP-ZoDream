<?php
namespace Module\Wechat\Domain\Model;

use Domain\Model\Model;


/**
 * 微信请求消息历史记录
 * @package callmez\wechat\models
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

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['wid', 'from', 'to', 'message', 'type'], 'required'],
            [['wid', 'rid', 'kid'], 'integer'],
            [['from', 'to'], 'string', 'max' => 50],
            [['module'], 'string', 'max' => 20],
            [['type'], 'string', 'max' => 10],

            [['module'], 'default', 'value' => function() {
                //return Yii::$app->controller->module->id;
            }]
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
            'module' => '处理模块',
            'message' => '消息体内容',
            'type' => '发送类型',
            'created_at' => '创建时间',
        ];
    }

    /**
     * 快速添加记录
     * @param array $data
     * @return bool|object 错误将返回model类
     */
    public static function add(array $data) {
        return static::create($data);
    }
}