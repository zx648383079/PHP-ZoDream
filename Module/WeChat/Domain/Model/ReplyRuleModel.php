<?php
namespace Module\WeChat\Domain\Model;

use Domain\Model\Model;


/**
 * 微信公众号用户资料表
 * 从公众号中拉取的数据可以保存在此表
 * @package callmez\wechat\models
 */
class ReplyRuleModel extends Model {
    /**
     * 激活状态
     */
    const STATUS_ACTIVE = 1;
    /**
     * 禁用状态
     */
    const STATUS_DISABLED = 0;
    const PROCESSOR_DEFAULT = 'process';
    public static $statuses = [
        self::STATUS_ACTIVE => '启用',
        self::STATUS_DISABLED => '禁用'
    ];

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'wechat_reply_rule';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['wid', 'name', 'mid', 'status'], 'required'],
            [['wid', 'status', 'priority'], 'integer'],
            [['name', 'processor'], 'string', 'max' => 40],
            [['mid'], 'string', 'max' => 20],
            [['priority'], 'default', 'value' => 0],
            [['processor'], 'default', 'value' => self::PROCESSOR_DEFAULT]
        ];
    }

    /**
     * @inheritdoc
     */
    public function labels() {
        return [
            'id' => 'ID',
            'wid' => '所属微信公众号ID',
            'name' => '规则名称',
            'mid' => '处理模块',
            'processor' => '请求处理类',
            'status' => '状态',
            'priority' => '优先级',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',

            'keywords' => '触发关键字'
        ];
    }

    /**
     * 回复的关键字
     * @return static
     */
    public function getKeywords() {
        return $this->hasMany(ReplyRuleKeyword::className(), ['rid' => 'id'])
            ->inverseOf('rule');
    }
}